<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogModel;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Meet;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        $user = $request->user();

        if($user->role == 'USER' || $user->role == 'SPECIALIST'){

            return view('home.index_user',compact('user'));
        }

        //GET NOTIFICATIONS

        $logsQuery = LogModel::query();

        if ($user->role != User::ROLE_ADMIN) {
            $logsQuery->whereIn('user_role', [User::ROLE_MODERATOR, User::ROLE_SPECIALIST]);
        }

        $logsQuery->orderBy('created_at', 'desc');

        // Paginate the categories
        $logs = $logsQuery->paginate(6);

        // Get the requested page from the query string
        $page = $request->query('page');

        if ($page && ($page < 1 || $page > $logs->lastPage())) {
            return redirect()->route('home.index');
        }

        $searchParam = $page ? $page : '';

        //GET TOTAL IMPORT
        $filterImport = $request->query('import');
        $import_total = 0;

        if ($filterImport == 1 || !$filterImport) {
            $startOfMonth = now()->startOfMonth();
            $import_total = Meet::where('payment_status', 'BILLED')
                ->where('created_at', '>=', $startOfMonth)
                ->sum('discounted_price');
        } elseif ($filterImport == 2) {
            $lastMonthStart = now()->subMonth()->startOfMonth();
            $lastMonthEnd = now()->subMonth()->endOfMonth();
            $import_total = Meet::where('payment_status', 'BILLED')
                ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
                ->sum('discounted_price');
        } elseif ($filterImport == 3) {
            $threeMonthsAgo = now()->subMonths(3);
            $import_total = Meet::where('payment_status', 'BILLED')
                ->where('created_at', '>=', $threeMonthsAgo)
                ->sum('discounted_price');
        }        

        //GET IMPORT in the last 7 days
        $lastSevenDays = now()->subDays(7);

        $import_last_days = Meet::where('payment_status', 'BILLED')
            ->where('created_at', '>=', $lastSevenDays)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->select(DB::raw('DATE(created_at) as created_date'), DB::raw('SUM(discounted_price) as total_amount'))
            ->get();

        //GET MEETINGS BY SPECIALIST
        // Calculate the date 7 months ago
        $sevenMonthsAgo = Carbon::now()->subMonths(7);

        // Fetch the meetings data with joins
        $meetingsData = Meet::selectRaw('DATE_FORMAT(meets.created_at, "%Y-%m") as month')
            ->selectRaw('services.name as service_name')
            ->selectRaw('COUNT(*) as quantity')
            ->join('services', 'meets.service_id', '=', 'services.id')
            ->where('meets.created_at', '>=', $sevenMonthsAgo)
            ->groupBy('month', 'service_name')
            ->orderBy('month', 'asc')
            ->orderBy('service_name', 'asc')
            ->get();


        return view('home.index',compact('logs','import_last_days','import_total','meetingsData','searchParam'));
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
}
