<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Meet;
use App\Models\SpecialistTimes;
use App\Models\Specialist;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\JoinUs;
use App\Mail\ReplyJoinUs;
use Illuminate\Support\Facades\Mail;


class ApiWebController extends Controller
{

    public function getTimeSheets(Request $request){

        //TIMESHEETS
        $specialiststimesQuery = SpecialistTimes::query();
        // Select the desired fields, including user.name
        $range = $request->query('range');
        $page = $request->query('page');
        if($range > 0 && $page >= 0){

            $currentDay = now()->setSeconds(0)->setMinutes(0)->setHours(0);
            $startDay = $currentDay->copy()->addDays($range * $page);
            $followingXDays = $currentDay->copy()->addDays($range * ($page + 1));

            $followingXDays->setHours(23)->setMinutes(59)->setSeconds(59);
            $specialiststimesQuery->where('start_date', '<=', $followingXDays)
                ->where('end_date', '>=', $startDay);
        }else{
            //GET IMPORT in the last 7 days
            //
            $currentDay = now()->setSeconds(0)->setMinutes(0)->setHours(0);
            $followingSevenDays = now()->setSeconds(59)->setMinutes(59)->setHours(23)->addDays(7);

            $specialiststimesQuery->where('start_date', '<=',$followingSevenDays)
                ->where('end_date','>=', $currentDay);
        }

        $timesheets_following_seven_days = $specialiststimesQuery->select('id','specialist_id','start_date','end_date')
                                                                 ->orderBy(DB::raw('DATE(start_date)'))->get();


         // Fetch meetings that may overlap with timesheets
        $meetings = Meet::where(function ($query) use ($timesheets_following_seven_days) {
            foreach ($timesheets_following_seven_days as $timesheet) {
                $query->orWhere(function ($subQuery) use ($timesheet) {
                    $subQuery->where('date_meet', '<=', $timesheet->end_date)
                        ->where(DB::raw("DATE_ADD(date_meet, INTERVAL duration MINUTE)"), '>=', $timesheet->start_date);
                });
            }
        })->get();

        // Split the timesheets based on overlapping meetings
        $splitTimesheets = [];

        foreach ($timesheets_following_seven_days as $timesheet) {
            $remainingTimes = [(object) [
                'specialist_id' => $timesheet->specialist_id,
                'start_date' => Carbon::parse($timesheet->start_date),
                'end_date' => Carbon::parse($timesheet->end_date),
            ]];

            foreach ($meetings as $meeting) {
                if ($meeting->specialist_id == $timesheet->specialist_id) {
                    $newRemainingTimes = [];

                    foreach ($remainingTimes as $remainingTime) {
                        $dateMeet = Carbon::parse($meeting->date_meet); // Parse the string to create a Carbon instance
                        
                        if ($remainingTime->start_date >= $dateMeet->copy()->addMinutes($meeting->duration) ||
                            $remainingTime->end_date <= $dateMeet) {
                            // No overlap, keep the remaining time as is
                            $newRemainingTimes[] = $remainingTime;
                        } else {
                            if ($remainingTime->start_date < $dateMeet) {
                                $newRemainingTimes[] = (object) [
                                    'specialist_id' => $timesheet->specialist_id,
                                    'start_date' => $remainingTime->start_date,
                                    'end_date' => $dateMeet,
                                ];
                            }

                            if ($remainingTime->end_date > $dateMeet->copy()->addMinutes($meeting->duration)) {
                                $newRemainingTimes[] = (object) [
                                    'specialist_id' => $timesheet->specialist_id,
                                    'start_date' => $dateMeet->copy()->addMinutes($meeting->duration),
                                    'end_date' => $remainingTime->end_date,
                                ];
                            }
                        }
                    }

                    $remainingTimes = $newRemainingTimes;
                }
            }

            $splitTimesheets = array_merge($splitTimesheets, $remainingTimes);
        }


        //Specialist
        $specialisttimesQuery = Specialist::query();

        $specialistInTimesheets = [];

        foreach ($splitTimesheets as $timesheet) {
            $exists = false;
            foreach ($specialistInTimesheets as $existingTimesheet) {
                if ($timesheet->specialist_id === $existingTimesheet->specialist_id) {
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $specialistInTimesheets[] = $timesheet;
            }
        }

        $specialistIds = array_column($specialistInTimesheets, 'specialist_id'); // Extract specialist IDs

        $specialist = $specialisttimesQuery->whereIn('id', $specialistIds)
                ->where('is_active',1)
                ->select('id','services','sex','profile_image','summary','awards','experiences','educations','evaluated_rate')->get(); // Fixed the query
        foreach ($specialist as $specialistItem) {
            $specialistItem->profile_image = env('BACKEND_URL_IMAGE') . $specialistItem->profile_image;
            $specialistItem->services = json_decode($specialistItem->services);
            $specialistItem->experiences = json_decode($specialistItem->experiences);
            $specialistItem->educations = json_decode($specialistItem->educations);
            $specialistItem->awards = json_decode($specialistItem->awards);
        }

        $services = Service::where('is_active', 1)->select('id','name','options')->get();

        // Return a view or JSON response as desired
        return response()->json([
            'time_sheets' => $splitTimesheets,
            'specialist' => $specialist,
            'services' => $services
        ]);

    }

    public function joinUsForm(Request $request){

        // Check the Authorization header
        $authHeader = $request->header('Authorization');
        $AuthHeaderCode = env('MAIL_HEADER_SENDER');
        if ($authHeader !== $AuthHeaderCode) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $city = $request->input('city');
        $phone = $request->input('phone');

        $mailable = new JoinUs($name);
        Mail::to($email)->send($mailable);

        $mailableReply = new ReplyJoinUs($name, $email, $city, $phone);
        $recipients = ['administracion@nuna.com.pe','tsanchez@nuna.com.pe','kaldazabal@nuna.com.pe'];
        Mail::to($recipients)->send($mailableReply);  

    }

    public function callBackPayment(Request $request){

    }

}

