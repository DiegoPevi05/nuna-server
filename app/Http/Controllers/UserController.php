<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\LogService;

class UserController extends Controller
{

    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function searchByName(Request $request)
    {
        $name = strtolower($request->input('name')); // Convert input name to lowercase

        $users = User::whereRaw('LOWER(name) like ?', ["%$name%"])
            ->where('role', 'USER') // Filter by role = 'USER'
            ->select('id', 'name', 'email')
            ->limit(5)
            ->get();

        return response()->json($users);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Retrieve only verified users with pagination
        $usersQuery = User::whereNotNull('email_verified_at')->whereNotIn('role', ['ADMIN']);

        // Check if the email search parameter is provided
        $email = $request->query('email');
        if ($email) {
            // Apply the email filter to the query
            $usersQuery->where('email', 'like', '%' . $email . '%');
        }

        // Paginate the filtered users
        $users = $usersQuery->paginate(10);

        // Get the requested page from the query string
        $page = $request->query('page');

        // Redirect to the first page if the requested page is not valid
        if ($page && ($page < 1 || $page > $users->lastPage())) {
            return redirect()->route('users.index');
        }

        // Pass the search parameter to the view
        $searchParam = $email ? $email : '';

        // Return a view or JSON response as desired
        return view('users.index', compact('users', 'searchParam'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate the request data, including the 'role' field
        $validatedData = $request->validate([
            'name' => 'required|max:25',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'role' => 'required|in:' . User::ROLE_USER . ',' . User::ROLE_MODERATOR,
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El campo nombre no puede tener más de 25 caracteres.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número, un carácter especial (@$!%*?&), y tener al menos 8 caracteres.',
            'role.required' => 'El campo rol es obligatorio.',
            'role.in' => 'El valor del campo rol es inválido.',
        ]);

        // Create a new user with the specified role
        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
            'email_verified_at' => now(),
        ]);

        $return_message = 'Usuario creado exitosamente.';

        $this->logService->Log(1,$return_message);

        // Return a success response or redirect as desired
        return redirect()->route('users.index')->with('logSuccess', $return_message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:25',
            'email' => 'required|email|unique:users,email,'. $user->id,
            'password' => 'nullable|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'role' => 'required|in:' . User::ROLE_USER . ',' . User::ROLE_MODERATOR. ','. User::ROLE_SPECIALIST,
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El campo nombre no puede tener más de 25 caracteres.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula, un número, un carácter especial (@$!%*?&), y tener al menos 8 caracteres.',
            'role.required' => 'El campo rol es obligatorio.',
            'role.in' => 'El valor del campo rol es inválido.',
        ]);

        // Update user with the specified role
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];

        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        $user->role = $validatedData['role'];
        $user->save();

        $return_message = 'Usuario actualizado exitosamente.';

        $this->logService->Log(1,$return_message);

        // Return a success response or redirect as desired
        return redirect()->route('users.index')->with('logSuccess', $return_message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Delete the specified user
        $user->delete();

        $return_message = 'Usuario borrado exitosamente.';

        $this->logService->Log(1,$return_message);

        // Return a success response or redirect as desired
        return redirect()->route('users.index')->with('logSuccess', $return_message);
    }
}
