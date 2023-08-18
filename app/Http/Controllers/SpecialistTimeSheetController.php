<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LogService;
use App\Models\SpecialistTimes;
use App\Models\Specialist;

class SpecialistTimeSheetController extends Controller
{

    protected $logService;

    public function __construct(LogService $logService)
    {
        $this->logService = $logService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $specialiststimesQuery = SpecialistTimes::query()
        ->join('specialists', 'specialist_times.specialist_id', '=', 'specialists.id') // Join with specialists table
        ->join('users', 'specialists.user_id', '=', 'users.id'); // Join with users table

        // Select the desired fields, including user.name
        $specialiststimesQuery->select('specialist_times.*', 'users.name as user_name');

        $user = $request->user();

        $specialiststimesQuery->where('users.id',$user->id);

        // Check if the name search parameter is provided
        $name = $request->query('name');
        if ($name) {
            // Apply the name filter to the query using users.name
            $specialiststimesQuery->where('users.name', 'like', '%' . $name . '%');
        }

        // Paginate the categories
        $specialist_specialiststimes = $specialiststimesQuery->paginate(10);

        // Get the requested page from the query string
        $page = $request->query('page');

        // Redirect to the first page if the requested page is not valid
        if ($page && ($page < 1 || $page > $specialist_specialiststimes->lastPage())) {
            return redirect()->route('specialist-specialiststimes.index');
        }

        $searchParam = $name ? $name : '';

        // Return a view or JSON response as desired
        return view('specialist-specialiststimes.index', compact('specialist_specialiststimes', 'searchParam'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = $request->user();
        $specialist = Specialist::where('user_id',$user->id)->first();
        return view('specialist-specialiststimes.create',compact('specialist'));
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
            'specialist_id' => 'required|integer|exists:specialists,id',
            'start_date' => 'required|date_format:Y-m-d\TH:i', // Adjusted for datetime-local
            'end_date' => 'required|date_format:Y-m-d\TH:i|after:start_date', // Adjusted for datetime-local
        ], [
            'specialist_id.required' => 'El ID del especialista es obligatorio.',
            'specialist_id.integer' => 'El ID del especialista debe ser un número entero.',
            'specialist_id.exists' => 'El ID del especialista no existe en la tabla de especialistas.',
            'start_date.required' => 'La fecha y hora de inicio es obligatoria.', // Updated message
            'start_date.date_format' => 'La fecha y hora de inicio debe tener el formato válido.', // Updated message
            'end_date.required' => 'La fecha y hora de fin es obligatoria.', // Updated message
            'end_date.date_format' => 'La fecha y hora de fin debe tener el formato válido.', // Updated message
            'end_date.after' => 'La fecha y hora de fin debe ser posterior a la fecha de inicio.', // Updated message
        ]);


        $specialisttime = SpecialistTimes::create([
            'specialist_id' => $validatedData['specialist_id'], // Add the user_id field
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date']
        ]);

        $return_message = 'Tiempo de Especialista creado exitosamente.';

        $this->logService->Log(1,$return_message);

        return redirect()->route('specialist-specialiststimes.index')->with('logSuccess', $return_message);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(SpecialistTimes $specialist_specialiststime)
    {
        return view('specialist-specialiststimes.show', compact('specialist_specialiststime'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(SpecialistTimes $specialist_specialiststime)
    {
        return view('specialist-specialiststimes.edit', compact('specialist_specialiststime'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SpecialistTimes $specialist_specialiststime)
    {
        $validatedData = $request->validate([
            'specialist_id' => 'required|integer|exists:specialists,id',
            'start_date' => 'required|date_format:Y-m-d\TH:i', // Adjusted for datetime-local
            'end_date' => 'required|date_format:Y-m-d\TH:i|after:start_date', // Adjusted for datetime-local
        ], [
            'specialist_id.required' => 'El ID del especialista es obligatorio.',
            'specialist_id.integer' => 'El ID del especialista debe ser un número entero.',
            'specialist_id.exists' => 'El ID del especialista no existe en la tabla de especialistas.',
            'start_date.required' => 'La fecha y hora de inicio es obligatoria.', // Updated message
            'start_date.date_format' => 'La fecha y hora de inicio debe tener el formato válido.', // Updated message
            'end_date.required' => 'La fecha y hora de fin es obligatoria.', // Updated message
            'end_date.date_format' => 'La fecha y hora de fin debe tener el formato válido.', // Updated message
            'end_date.after' => 'La fecha y hora de fin debe ser posterior a la fecha de inicio.', // Updated message
        ]);

        $specialist_specialiststime ->update([
            'specialist_id' => $validatedData['specialist_id'], // Add the user_id field
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date']
        ]);

        $return_message = 'Tiempo de Especialista actualizado exitosamente.';

        $this->logService->Log(1,$return_message);

        return redirect()->route('specialist-specialiststimes.index')->with('logSuccess', $return_message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SpecialistTimes $specialiststime)
    {
        // Delete the specifiedSpecialist 
        $specialiststime->delete();

        $return_message = 'Tiempo de Especialista borrado exitosamente.';

        $this->logService->Log(1,$return_message);
        // Return a success response or redirect as desired
        return redirect()->route('specialist-specialiststimes.index')->with('logSuccess', $return_message);
    }
}
