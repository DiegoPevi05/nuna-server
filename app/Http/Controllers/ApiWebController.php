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
use App\Models\DiscountCode;
use App\Services\PaymentService;
use App\Services\MeetService;


class ApiWebController extends Controller
{

    protected $meetService;

    protected $paymentService;

    public function __construct(MeetService $meetService, PaymentService $paymentService)
    {
        $this->meetService = $meetService;
        $this->paymentService = $paymentService;
    }

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

    public function checkDiscountCode(Request $request){
        $authHeader = $request->header('Authorization');
        $AuthHeaderCode = env('MAIL_HEADER_SENDER');
        if ($authHeader !== $AuthHeaderCode) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $discount_name = $request->input('discount_code');

        // Trim the spaces from the input value
        $trimmed_discount_name = trim($discount_name);

        // Retrieve the discount code from the database and compare in uppercase
        $discount_code_db = DiscountCode::whereRaw('UPPER(name) = ?', [strtoupper($trimmed_discount_name)])->first();

        if(!$discount_code_db){
            return response()->json(['error' => 'Bad Request', 'message' => 'El codigo de descuento no existe.'], 400);
        }

        if($discount_code_db->quantity_discounts == 0){
            return response()->json(['error' => 'Bad Request', 'message' => 'El descuento aplicado ya no se puede aplicar valido.'], 400);
        }

        if($discount_code_db->status == 'inactive'){
            return response()->json(['error' => 'Bad Request', 'message' => 'El descuento aplicado no ya no es valido.'], 400);
        }

        $now = Carbon::now();
        if($discount_code_db->expired_date < $now){
            return response()->json(['error' => 'Bad Request', 'message' => 'El descuento aplicado ya vencio.'], 400);
        }

        return response()->json([
            'discount_id' => $discount_code_db->id,
            'discount_code' => $discount_code_db->name,
            'discount_amount' => $discount_code_db->discount
        ]);

    }

    public function makeOrder(Request $request){

            // Define validation rules for each field
            $rules = [
                'sessions' => 'required|array',
                'sessions.*.service_id' => 'required|exists:services,id',
                'sessions.*.specialist_id' => 'required|exists:specialists,id',
                'sessions.*.specialist_name' => 'required|string',
                'sessions.*.service_name' => 'required|string',
                'sessions.*.option.id' => 'required|numeric',
                'sessions.*.option.price' => 'required|numeric',
                'sessions.*.option.duration' => 'required|numeric',
                'sessions.*.datetime' => 'required|date',
                'discount.discount_id' => 'nullable|numeric',
                'discount.discount_code' => 'nullable|string',
                'discount.discount_amount' => 'nullable|numeric',
            ];

            // Custom error messages
            $messages = [
                'sessions.required' => 'Es necesario enviar alguna session.',
                'sessions.array' => 'Es necesario enviar alguna session.',
                'sessions.*.service_id.required' => 'Es necesario el id del servicio.',
                'sessions.*.service_id.exists' => 'El servicio asigando no existe',
                'sessions.*.specialist_id.required' => 'El id del especialista es requerido.',
                'sessions.*.specialist_id.exists' => 'El id del especialista es requerido.',
                'sessions.*.specialist_name.required' => 'El nombre del especialista es requerido.',
                'sessions.*.specialist_name.string' => 'El nombre del especialista debe ser un texto.',
                'sessions.*.service_name.required' => 'El nombre del servicio es requerido.',
                'sessions.*.service_name.string' => 'El nombre del servicio debe ser un texto',
                'sessions.*.option.id.required' => 'El identificador de la opcion es requerido',
                'sessions.*.option.id.numeric' => 'El identificador de la opcion debe ser un numero.',
                'sessions.*.option.price.required' => 'El precio de la opcione es requerido.',
                'sessions.*.option.price.numeric' => 'El precio de la opcion debe ser un numero.',
                'sessions.*.option.duration.required' => 'La duracion de la opcion es requerida.',
                'sessions.*.option.duration.numeric' => 'La duracion de la opcion es requerida.',
                'sessions.*.datetime.required' => 'La fecha y hora son requeridas.',
                'sessions.*.datetime.date' => 'La fecha y hora deben ser en formato fecha.',
                'discount.discount_id.numeric' => 'El identificador de descuento debe ser un Id.',
                'discount.discount_code.string' => 'El codigo de descuento debe ser un texto.',
                'discount.discount_amount.numeric' => 'El descuento debe ser un numero.',
            ];

            try {
                // Validate the request data
                $validatedData = $request->validate($rules, $messages);

                $meets = [];

                // Iterate over each session
                foreach ($validatedData['sessions'] as $session) {
                    // Extract parameters for the CheckAvailability call
                    $dateMeet = $session['datetime'];
                    $specialistId = (int) $session['specialist_id'];
                    $serviceId = (int) $session['service_id'];
                    $serviceOptionId = (int) $session['option']['id'];

                    //VALIDATE AVAILABILITY
                    $response_checkavailability = $this->meetService->CheckAvailability(
                        $dateMeet,
                        $specialistId,
                        $serviceId,
                        $serviceOptionId
                    );

                    if(!$response_checkavailability['status']){
                        return response()->json([
                            'error' => 'Validation failed',
                            'messages' => $response_checkavailability['message']
                        ], 400);
                    }

                    //PRICE IS CALCULATED
                    $response_calculated_amount = $this->meetService->CalculateAmount(
                        (int)$session['service_id'],
                        (int)$session['option']['id'],
                        (int)$validatedData['discount']['discount_id'], 
                        (int)$validatedData['discount']['discount_amount'], 
                        (float)$session['option']['price'],
                        true
                    );

                    if(!$response_calculated_amount['status']){
                        return response()->json([
                            'error' => 'Validation failed',
                            'messages' => $response_calculated_amount['message']
                        ], 400);
                    }

                    $price = $response_calculated_amount['price'];
                    $discount = $response_calculated_amount['discount'];
                    $discounted_price = $response_calculated_amount['discounted_price'];

                    $dateMeet = Carbon::parse($session['datetime'])
                                ->timezone('GMT-5') // Adjusting the timezone to GMT-5
                                ->setSeconds(0); // Set seconds to 0

                    $meet = Meet::create([
                        'user_id' => 4,
                        'specialist_id' => $session['specialist_id'],
                        'service_id' => $session['service_id'],
                        'service_option_id' => $session['option']['id'],
                        'duration' => $session['option']['duration'],
                        'discount_code_id' => $validatedData['discount']['discount_id'] > 0 ? $validatedData['discount']['discount_id'] : null,
                        'date_meet' => $dateMeet,
                        'price_calculated' => true,
                        'price' => $price,
                        'discount' => $discount,
                        'discounted_price' => $discounted_price,
                        'payment_status' => Meet::PAYMENT_STATUS_2,
                    ]);
                    
                    $meets[] = $meet;
                    

                }

                $response_payment = $this->paymentService->makePayment($meets, 'Pago de servicio nuna', 'Servicios de Psicologia y Terapia');

                if($response_payment['status']){
                    foreach($meets as $meet){
                        $meet->create_payment = true;
                        $meet->reference_id = $response_payment['preference_id']; 
                        $meet->payment_link = $response_payment['init_point'];
                        $meet->external_reference = $response_payment['external_reference']; 
                        $meet->save();
                    }
                }else{

                    $return_message = $response_payment['payment_message'];

                    return response()->json([
                        'error' => 'Validation failed',
                        'messages' => $return_message
                    ], 400);
                }
                    

                return response()->json([
                    'success' => 'Executed Request',
                    'init_point'=> $response_payment['init_point']
                ], 200);

            } catch (ValidationException $e) {
                // Return a 400 Bad Request response with the error message
                return response()->json([
                    'error' => 'Validation failed',
                    'messages' => $e->errors()
                ], 400);
            }
    }

    //api/web/confirmationpayment?externalReference='
    // \Illuminate\Support\Facades\Log::debug('Meets Retrieved:', $meets->toArray());
    public function callBackPayment(Request $request){

        $externalReference = $request->query('externalReference');
        
        if ($externalReference) {

            $meets = Meet::where('external_reference', $externalReference)->get();
            $paymentStatus = $request->input('status');

            if ($paymentStatus == 'approved') {
                foreach($meets as $meet){
                    $meet->payment_status = Meet::PAYMENT_STATUS_3;
                    
                    $meet->create_link_meet = true;

                    $response_generate_link = [];

                    $response_generate_link = $this->meetService->GenerateMeetLink(
                        (int)$meet->user_id, 
                        (int)$meet->specialist_id,
                        $meet->date_meet,
                        (int)$meet->duration
                    );

                    if($response_generate_link['status']){

                        $meet->link_meet = $response_generate_link['link_meet'];

                    }else{

                        $return_message = $response_generate_link['message'];
                        \Illuminate\Support\Facades\Log::debug('Logs Error Creation Meets', $return_message);
                    }

                    $meet->save();
                }

                return redirect()->away(env('FRONTEND_URL') . '/#confirmation-payment?status=approved&externalReference=' . $externalReference);

            } elseif ($paymentStatus == 'rejected') {

                foreach($meets as $meet){
                    $meet->payment_status = Meet::PAYMENT_STATUS_4;
                    $meet->save();
                }

                return redirect()->away(env('FRONTEND_URL') . '/#confirmation-payment?status=rejected&externalReference=' . $externalReference);

            } else {

                return redirect()->route('user-meets.index');
            }
        }
        return redirect()->route('user-meets.index');
    }

    public function getSessions(Request $request){
        $externalReference = $request->query('externalReference');
        if ($externalReference) {
            $meets = Meet::where('external_reference', $externalReference)->get();

            $meetsResponse = [];

            foreach($meets as $meet){
                $itemMeetResponse = [
                    'specialist_name' => $meet->specialist->user->name,
                    'service_name' => $meet->service->name,
                    'date_meet' =>  $meet->date_meet,
                    'duration' =>  $meet->duration,
                    'link_meet' =>  $meet->link_meet
                ];

                $meetsResponse[] = $itemMeetResponse;
            }

            return response()->json([
                'success' => 'Executed Request',
                'meets'=> $meetsResponse
            ], 200);

        }else{
            return response()->json([
                'error' => 'Validation failed',
                'messages' => "No hay referencia externa"
            ], 400);
        }
    }

}

