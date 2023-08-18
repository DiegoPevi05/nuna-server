<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meet;
use Illuminate\Support\Facades\Auth;
use App\Models\Specialist;

class UserMeetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $meetsQuery = Meet::query();

        // Check if the name search parameter is provided
        $date = $request->query('date');
        if ($date) {
            // Apply the date filter to the query
            $meetsQuery->whereDate('date_meet', $date);
        }

        // GET for Current User
        $user = $request->user();

        if($user->role == 'USER'){
            $meetsQuery->where('user_id',$user->id);
        }else{
            $specialist = Specialist::where('user_id',$user->id)->first();
            $meetsQuery->where('specialist_id',$specialist->id);
        }
        // Paginate the categories
        $user_meets = $meetsQuery->paginate(10);

        // Get the requested page from the query string
        $page = $request->query('page');

        // Redirect to the first page if the requested page is not valid
        if ($page && ($page < 1 || $page > $user_meets->lastPage())) {
            return redirect()->route('user-meets.index');
        }

        $searchParam = $date ? $date : '';

        // Return a view or JSON response as desired
        return view('user-meets.index', compact('user_meets', 'searchParam'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_meet = Meet::where('id', $id)->first();
        return view('user-meets.show', compact('user_meet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Meet $user_meet)
    {
        return view('user-meets.edit', compact('user_meet'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meet $user_meet)
    {

        $validatedData = $request->validate([
            'canceled' => 'nullable',
            'canceled_reason' => 'nullable|string',
            'survey_status' => 'nullable|string',
            'rate' => 'nullable|numeric',
            'comment' => 'nullable|string',
        ], [
            'canceled_reason.string' => 'El campo Razón de Cancelación debe ser una cadena de texto.',
            'survey_status.string' => 'El campo Estado de Encuesta debe ser una cadena de texto.',
            'rate.numeric' => 'El campo Tasa debe ser numérico.',
            'comment.string' => 'El campo Comentario debe ser una cadena de texto.',
        ]);

        $canceled = isset($validatedData['canceled']) && $validatedData['canceled'] ? true : false;


        $user_meet->update([
            'canceled' => $canceled,
            'canceled_reason' => $validatedData['canceled_reason'],
            'survey_status' => $validatedData['survey_status'],
            'rate' => $validatedData['rate'],
            'comment' => $validatedData['comment'],
        ]);

        return redirect()->route('meets.index')->with('logSuccess', 'Reunion actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
