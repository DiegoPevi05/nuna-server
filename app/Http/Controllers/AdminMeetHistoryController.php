<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MeetHistory;
use App\Models\Meet;
use PDF;

class AdminMeetHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $meetsHistoryQuery = MeetHistory::query();

        // Check if the name search parameter is provided
        $date = $request->query('date');
        if ($date) {
            // Apply the date filter to the query
            $meetsHistoryQuery->whereDate('date_meet', $date);
        }

        // Paginate the categories
        $meethistories = $meetsHistoryQuery->paginate(10);

        // Get the requested page from the query string
        $page = $request->query('page');

        // Redirect to the first page if the requested page is not valid
        if ($page && ($page < 1 || $page > $meethistories->lastPage())) {
            return redirect()->route('meethistories.index');
        }

        $searchParam = $date ? $date : '';

        // Return a view or JSON response as desired
        return view('meethistories.index', compact('meethistories', 'searchParam'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function downloadBill($id){
        $meet = Meet::where('id', $id )->first();
        $pdf = PDF::loadView('pdf.bills', compact('meet','id'));
        return $pdf->download('bill_' . $id . '.pdf');
    }
}
