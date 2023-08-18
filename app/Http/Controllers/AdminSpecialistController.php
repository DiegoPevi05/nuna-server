<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Specialist;
use App\Models\User;
use App\Services\LogService;

class AdminSpecialistController extends Controller
{

    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }

    public function searchByName(Request $request)
    {
        // Enable query logging
        //DB::connection()->enableQueryLog();

        $name = strtolower($request->input('name')); // Convert input name to lowercase

        $specialists = Specialist::join('users', 'specialists.user_id', '=', 'users.id')
            ->where('is_active', true)
            ->whereRaw('LOWER(users.name) like ?', ["%$name%"])
            ->select('specialists.id', 'users.name as user_name')
            ->limit(5)
            ->get();

        // Get the executed queries
        //$queries = DB::getQueryLog();

        // Dump the queries to the debug log
        //\Illuminate\Support\Facades\Log::debug('Executed Queries:', $queries);

        return response()->json($specialists);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $specialistsQuery = Specialist::query()->join('users', 'specialists.user_id', '=', 'users.id');

        // Check if the name search parameter is provided
        $name = $request->query('name');
        if ($name) {
            // Apply the name filter to the query
            $specialistsQuery->where('users.name', 'like', '%' . $name . '%');
        }

        // Paginate the categories
        $specialists = $specialistsQuery->with('user')->paginate(10);

        // Decode the 'options' field for each service
        foreach ($specialists as $specialist) {
            $specialist->services       = json_decode($specialist->services);
            $specialist->awards         = json_decode($specialist->awards);
            $specialist->experience     = json_decode($specialist->experience);
            $specialist->education      = json_decode($specialist->education);
        }

        // Get the requested page from the query string
        $page = $request->query('page');

        // Redirect to the first page if the requested page is not valid
        if ($page && ($page < 1 || $page > $specialists->lastPage())) {
            return redirect()->route('specialists.index');
        }

        $searchParam = $name ? $name : '';

        // Return a view or JSON response as desired
        return view('specialists.index', compact('specialists', 'searchParam'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('specialists.create')->with('editMode', false);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'services' => 'required|array',
            'services.*.id_service' => 'required|integer',
            'services.*.name_service' => 'required|string',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string',
            'sex' => 'required|in:M,F,O',
            'profile_image' => 'nullable|image|mimes:jpeg,png,webp',
            'type_document' => 'required|in:DNI,PASSPORT',
            'document_id' => 'required|integer',
            'summary' => 'nullable|string',
            'awards' => 'nullable|array',
            'awards.*.header' => 'required|string',
            'awards.*.subheader' => 'required|string',
            'awards.*.date' => 'required|date',
            'experiences' => 'nullable|array',
            'experiences.*.header' => 'required|string',
            'experiences.*.subheader' => 'required|string',
            'experiences.*.from' => 'required|date',
            'experiences.*.to' => 'nullable|date',
            'experiences.*.is_active' => 'nullable',
            'educations' => 'nullable|array',
            'educations.*.header' => 'required|string',
            'educations.*.subheader' => 'required|string',
            'educations.*.from' => 'required|date',
            'educations.*.to' => 'nullable|date',
            'educations.*.is_active' => 'nullable',
            'evaluated_rate' => 'nullable|numeric',
            'is_active' => 'nullable',
            'birthdate' => 'nullable|date',
        ], [
            'user_id.required' => 'El ID de usuario es obligatorio.',
            'user_id.integer' => 'El ID de usuario debe ser un número entero.',
            'user_id.exists' => 'El ID de usuario no existe en la tabla de usuarios.',
            'services.required' => 'Los servicios son obligatorios.',
            'services.array' => 'Los servicios deben ser un arreglo.',
            'services.*.id_service.required' => 'El ID del servicio es obligatorio.',
            'services.*.id_service.integer' => 'El ID del servicio debe ser un número entero.',
            'services.*.name_service.required' => 'El nombre del servicio es obligatorio.',
            'services.*.name_service.string' => 'El nombre del servicio debe ser un texto.',
            'address.max' => 'La dirección no debe exceder los 255 caracteres.',
            'phone_number.string' => 'El número de teléfono debe ser texto.',
            'sex.required' => 'El género es obligatorio.',
            'sex.in' => 'El género debe ser "M", "F" o "O".',
            'type_document.required' => 'El tipo de documento es obligatorio.',
            'type_document.in' => 'El tipo de documento debe ser "DNI" o "PASSPORT".',
            'profile_image.image' => 'La imagen de perfil debe ser un archivo de imagen.',
            'profile_image.mimes' => 'La imagen de perfil debe ser un archivo en formato: jpeg, png, webp.',
            'document_id.integer' => 'El número de documento debe ser un número entero.',
            'summary.string' => 'El resumen debe ser un texto.',

            'awards.array' => 'Los premios deben ser un arreglo.',
            'awards.*.header.required' => 'El encabezado del premio es obligatorio.',
            'awards.*.header.string' => 'El encabezado del premio debe ser un texto.',
            'awards.*.subheader.required' => 'El subencabezado del premio es obligatorio.',
            'awards.*.subheader.string' => 'El subencabezado del premio debe ser un texto.',
            'awards.*.date.required' => 'La fecha  del premio es obligatoria.',
            'awards.*.date.date' => 'La fecha  del premio debe ser una fecha válida.',

            'experiences.array' => 'La experiencia debe ser un arreglo.',
            'experiences.*.header.required' => 'El encabezado de la experiencia es obligatorio.',
            'experiences.*.header.string' => 'El encabezado de la experiencia debe ser un texto.',
            'experiences.*.subheader.required' => 'El subencabezado de la experiencia es obligatorio.',
            'experiences.*.subheader.string' => 'El subencabezado de la experiencia debe ser un texto.',
            'experiences.*.from.required' => 'La fecha de inicio de la experiencia es obligatoria.',
            'experiences.*.from.date' => 'La fecha de inicio de la experiencia debe ser una fecha válida.',
            'experiences.*.to.required' => 'La fecha de finalización de la experiencia es obligatoria.',
            'experiences.*.to.date' => 'La fecha de finalización de la experiencia debe ser una fecha válida.',

            'educations.array' => 'La educación debe ser un arreglo.',
            'educations.*.header.required' => 'El encabezado de la educación es obligatorio.',
            'educations.*.header.string' => 'El encabezado de la educación debe ser un texto.',
            'educations.*.subheader.required' => 'El subencabezado de la educación es obligatorio.',
            'educations.*.subheader.string' => 'El subencabezado de la educación debe ser un texto.',
            'educations.*.from.required' => 'La fecha de inicio de la educación es obligatoria.',
            'educations.*.from.date' => 'La fecha de inicio de la educación debe ser una fecha válida.',
            'educations.*.to.required' => 'La fecha de finalización de la educación es obligatoria.',
            'educations.*.to.date' => 'La fecha de finalización de la educación debe ser una fecha válida.',

            'evaluated_rate.numeric' => 'La tasa evaluada debe ser un número.',
            'birthdate.date' => 'La fecha de nacimiento debe ser una fecha válida.',
        ]);

        //upload image
        $destinationPath = public_path() . '/images/specialists';
        $imageFileName = null;

        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            $file = $request->file('profile_image');
            $extension = $file->extension();
            $fileName = 'profile_' . time() . '.' . $extension;
            $file->move($destinationPath, $fileName);
            $imageFileName = $fileName;
        }


        // Convert the options array to JSON
        $educationsJson = null;
        if(isset($validatedData['educations'])){
            // Process the is_active property for educations and experiences arrays
            foreach ($validatedData['educations'] as &$education) {
                $education['is_active'] = isset($education['is_active']) && $education['is_active'] ? true : false;
            }

            $educationsJson = json_encode($validatedData['educations']);

        }

        $experiencesJson = null;
        if(isset($validatedData['experiences'])){
            
            foreach ($validatedData['experiences'] as &$experience) {
                $experience['is_active'] = isset($experience['is_active']) && $experience['is_active'] ? true : false;
            }

            $experiencesJson = json_encode($validatedData['experiences']);
        }

        $awardsJson = null;
        if(isset($validatedData['awards'])){
            $awardsJson = json_encode($validatedData['awards']);
        }

        $servicesJson = json_encode($validatedData['services']);


        $is_active = isset($validatedData['is_active']) && $validatedData['is_active'] ? true : false;

        $specialist = Specialist::create([
            'user_id' => $validatedData['user_id'], // Add the user_id field
            'services' => $servicesJson,
            'address' => $validatedData['address'],
            'phone_number' => $validatedData['phone_number'],
            'sex' => $validatedData['sex'],
            'profile_image' => $imageFileName ? '/images/specialists/' . $imageFileName : null,
            'type_document' => $validatedData['type_document'],
            'document_id' => $validatedData['document_id'],
            'summary' => $validatedData['summary'],
            'awards' => $awardsJson,
            'experiences' => $experiencesJson, // Use the decoded JSON variable
            'educations' => $educationsJson, // Use the decoded JSON variable
            'evaluated_rate' => $validatedData['evaluated_rate'],
            'is_active' => $is_active,
            'birthdate' => $validatedData['birthdate'],
        ]);

        //Update status user

        $user = User::where('id',$validatedData['user_id'])->first();
        $user->role = USER::ROLE_SPECIALIST;
        $user->save();

        $return_message = 'Especialista creado exitosamente.';

        $this->logService->Log(1,$return_message);

        return redirect()->route('specialists.index')->with('logSuccess', $return_message);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Specialist $specialist)
    {
        return view('specialists.show', compact('specialist'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Specialist $specialist)
    {
        return view('specialists.edit', compact('specialist'))->with('editMode', true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Specialist $specialist)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'services' => 'required|array',
            'services.*.id_service' => 'required|integer',
            'services.*.name_service' => 'required|string',
            'address' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string',
            'sex' => 'required|in:M,F,O',
            'profile_image' => 'nullable|image|mimes:jpeg,png,webp',
            'type_document' => 'required|in:DNI,PASSPORT',
            'document_id' => 'nullable|integer',
            'summary' => 'nullable|string',
            'awards' => 'nullable|array',
            'awards.*.header' => 'required|string',
            'awards.*.subheader' => 'required|string',
            'awards.*.date' => 'required|date',
            'experiences' => 'nullable|array',
            'experiences.*.header' => 'required|string',
            'experiences.*.subheader' => 'required|string',
            'experiences.*.from' => 'required|date',
            'experiences.*.to' => 'nullable|date',
            'experiences.*.is_active' => 'nullable',
            'educations' => 'nullable|array',
            'educations.*.header' => 'required|string',
            'educations.*.subheader' => 'required|string',
            'educations.*.from' => 'required|date',
            'educations.*.to' => 'nullable|date',
            'educations.*.is_active' => 'nullable',
            'evaluated_rate' => 'nullable|numeric',
            'is_active' => 'nullable',
            'birthdate' => 'nullable|date',
        ], [
            'user_id.required' => 'El ID de usuario es obligatorio.',
            'user_id.integer' => 'El ID de usuario debe ser un número entero.',
            'user_id.exists' => 'El ID de usuario no existe en la tabla de usuarios.',
            'services.required' => 'Los servicios son obligatorios.',
            'services.array' => 'Los servicios deben ser un arreglo.',
            'services.*.id_service.required' => 'El ID del servicio es obligatorio.',
            'services.*.id_service.integer' => 'El ID del servicio debe ser un número entero.',
            'services.*.name_service.required' => 'El nombre del servicio es obligatorio.',
            'services.*.name_service.string' => 'El nombre del servicio debe ser un texto.',
            'address.max' => 'La dirección no debe exceder los 255 caracteres.',
            'phone_number.string' => 'El número de teléfono debe ser texto.',
            'sex.required' => 'El género es obligatorio.',
            'sex.in' => 'El género debe ser "M", "F" o "O".',
            'type_document.required' => 'El tipo de documento es obligatorio.',
            'type_document.in' => 'El tipo de documento debe ser "DNI" o "PASSPORT".',
            'profile_image.image' => 'La imagen de perfil debe ser un archivo de imagen.',
            'profile_image.mimes' => 'La imagen de perfil debe ser un archivo en formato: jpeg, png, webp.',
            'document_id.integer' => 'El número de documento debe ser un número entero.',
            'summary.string' => 'El resumen debe ser un texto.',

            'awards.array' => 'Los premios deben ser un arreglo.',
            'awards.*.header.required' => 'El encabezado del premio es obligatorio.',
            'awards.*.header.string' => 'El encabezado del premio debe ser un texto.',
            'awards.*.subheader.required' => 'El subencabezado del premio es obligatorio.',
            'awards.*.subheader.string' => 'El subencabezado del premio debe ser un texto.',
            'awards.*.date.required' => 'La fecha  del premio es obligatoria.',
            'awards.*.date.date' => 'La fecha  del premio debe ser una fecha válida.',

            'experiences.array' => 'La experiencia debe ser un arreglo.',
            'experiences.*.header.required' => 'El encabezado de la experiencia es obligatorio.',
            'experiences.*.header.string' => 'El encabezado de la experiencia debe ser un texto.',
            'experiences.*.subheader.required' => 'El subencabezado de la experiencia es obligatorio.',
            'experiences.*.subheader.string' => 'El subencabezado de la experiencia debe ser un texto.',
            'experiences.*.from.required' => 'La fecha de inicio de la experiencia es obligatoria.',
            'experiences.*.from.date' => 'La fecha de inicio de la experiencia debe ser una fecha válida.',
            'experiences.*.to.required' => 'La fecha de finalización de la experiencia es obligatoria.',
            'experiences.*.to.date' => 'La fecha de finalización de la experiencia debe ser una fecha válida.',

            'educations.array' => 'La educación debe ser un arreglo.',
            'educations.*.header.required' => 'El encabezado de la educación es obligatorio.',
            'educations.*.header.string' => 'El encabezado de la educación debe ser un texto.',
            'educations.*.subheader.required' => 'El subencabezado de la educación es obligatorio.',
            'educations.*.subheader.string' => 'El subencabezado de la educación debe ser un texto.',
            'educations.*.from.required' => 'La fecha de inicio de la educación es obligatoria.',
            'educations.*.from.date' => 'La fecha de inicio de la educación debe ser una fecha válida.',
            'educations.*.to.required' => 'La fecha de finalización de la educación es obligatoria.',
            'educations.*.to.date' => 'La fecha de finalización de la educación debe ser una fecha válida.',

            'evaluated_rate.numeric' => 'La tasa evaluada debe ser un número.',
            'birthdate.date' => 'La fecha de nacimiento debe ser una fecha válida.',
        ]);


        $destinationPath = public_path() . '/images/specialists';
        $imageOldFilename = $specialist->profile_image;
        $imageFileName = null;

        if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
            $file = $request->file('profile_image');
            $extension = $file->extension();
            $fileName = 'profile_' . time() . '.' . $extension;
            $file->move($destinationPath, $fileName);
            $imageFileName = $fileName;

            if ($imageOldFilename !== null) {
                $oldImagePath = public_path($imageOldFilename);
                if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
        }else{
            if($specialist->profile_image !== null){
                $imageFileName = basename($specialist->profile_image);
            }
        }

        // Convert the options array to JSON
        $educationsJson = null;
        if(isset($validatedData['educations'])){
            // Process the is_active property for educations and experiences arrays
            foreach ($validatedData['educations'] as &$education) {
                $education['is_active'] = isset($education['is_active']) && $education['is_active'] ? true : false;
            }

            $educationsJson = json_encode($validatedData['educations']);

        }

        $experiencesJson = null;
        if(isset($validatedData['experiences'])){
            
            foreach ($validatedData['experiences'] as &$experience) {
                $experience['is_active'] = isset($experience['is_active']) && $experience['is_active'] ? true : false;
            }

            $experiencesJson = json_encode($validatedData['experiences']);
        }

        $awardsJson = null;
        if(isset($validatedData['awards'])){
            $awardsJson = json_encode($validatedData['awards']);
        }

        $servicesJson = json_encode($validatedData['services']);


        $is_active = isset($validatedData['is_active']) && $validatedData['is_active'] ? true : false;

        $specialist->update([
            'user_id' => $validatedData['user_id'], // Add the user_id field
            'services' => $servicesJson,
            'address' => $validatedData['address'],
            'phone_number' => $validatedData['phone_number'],
            'sex' => $validatedData['sex'],
            'profile_image' => $imageFileName ? '/images/specialists/' . $imageFileName : null,
            'type_document' => $validatedData['type_document'],
            'document_id' => $validatedData['document_id'],
            'summary' => $validatedData['summary'],
            'awards' => $awardsJson,
            'experiences' => $experiencesJson, // Use the decoded JSON variable
            'educations' => $educationsJson, // Use the decoded JSON variable
            'evaluated_rate' => $validatedData['evaluated_rate'],
            'is_active' => $is_active,
            'birthdate' => $validatedData['birthdate'],
        ]);

        //Update status user

        $user = User::where('id',$validatedData['user_id'])->first();
        $user->role = USER::ROLE_SPECIALIST;
        $user->save();

        $return_message = 'Especialista actualizado exitosamente.';

        $this->logService->Log(1,$return_message);

        return redirect()->route('specialists.index')->with('logSuccess', $return_message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Specialist $specialist)
    {
        $imageProfile = $partner->profile_image;

        if ($imageProfile !== null) {
            $ImagePath = public_path($imageProfile);
            if (file_exists($ImagePath) && is_file($ImagePath)) {
                unlink($ImagePath);
            }
        }

        $user = User::where('id',$specialist->user_id)->first();
        $user->role = USER::ROLE_USER;
        $user->save();

        // Delete the specifiedSpecialist 
        $specialist->delete();

        $return_message = 'Especialista borrado exitosamente.';

        $this->logService->Log(1,$return_message);

        // Return a success response or redirect as desired
        return redirect()->route('specialists.index')->with('logSuccess', $return_message);
    }
}
