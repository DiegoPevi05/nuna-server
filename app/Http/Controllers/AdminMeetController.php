<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Meet;
use App\Models\MeetHistory;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyMeet;
use Carbon\Carbon;
use App\Services\MeetService;
use Illuminate\Support\Facades\Log;
use App\Services\LogService;
use App\Services\PaymentService;


class AdminMeetController extends Controller
{

    protected $logService;

    protected $meetService;

    protected $paymentService;

    public function __construct(MeetService $meetService, LogService $logService, PaymentService $paymentService)
    {
        $this->meetService = $meetService;
        $this->logService = $logService;
        $this->paymentService = $paymentService;
    }
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

        // Paginate the categories
        $meets = $meetsQuery->paginate(10);

        // Get the requested page from the query string
        $page = $request->query('page');

        // Redirect to the first page if the requested page is not valid
        if ($page && ($page < 1 || $page > $meets->lastPage())) {
            return redirect()->route('meets.index');
        }

        $searchParam = $date ? $date : '';

        // Return a view or JSON response as desired
        return view('meets.index', compact('meets', 'searchParam'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('meets.create')->with('editMode', false);
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
            'user_id' => 'required|exists:users,id',
            'specialist_id' => 'required|exists:specialists,id',
            'service_id' => 'required|exists:services,id',
            'service_option_id' => 'required',
            'duration'=> 'required|numeric',
            'discount_code_id' => 'nullable',
            'date_meet' => 'required|date_format:Y-m-d\TH:i',
            'price_calculated' => 'nullable',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'discounted_price' => 'required|numeric',
            'canceled' => 'nullable',
            'canceled_reason' => 'nullable|string',
            'create_link_meet' => 'nullable',
            'link_meet' => 'nullable|string',
            'create_payment' => 'nullable',
            'reference_id' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'payment_id' => 'nullable|string',
            'survey_status' => 'nullable|string',
            'rate' => 'nullable|numeric',
            'comment' => 'nullable|string',
        ], [
            'user_id.required' => 'El campo Usuario es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no es válido.',
            'specialist_id.required' => 'El campo Especialista es obligatorio.',
            'specialist_id.exists' => 'El especialista seleccionado no es válido.',
            'service_id.required' => 'El campo Servicio es obligatorio.',
            'service_id.exists' => 'El servicio seleccionado no es válido.',
            'service_option_id.required' => 'La opcion del  servicio es obligatorio.',
            'duration.required' => 'La duración del meet es obligatorio.',
            'duration.numeric' => 'El campo debe ser numérico.',
            'date_meet.required' => 'El campo Fecha de Encuentro es obligatorio.',
            'date_meet.date_format' => 'El campo Fecha de Encuentro debe ser una fecha y hora válida.',
            'price.numeric' => 'El campo Precio debe ser numérico.',
            'price.required' => 'El campo Precio debe es obligatorio.',
            'discount.numeric' => 'El campo Descuento debe ser numérico.',
            'discounted_price.numeric' => 'El campo Precio Descontado debe ser numérico.',
            'discounted_price.required' => 'El campo Precio Descontado es obligatorio.',
            'canceled_reason.string' => 'El campo Razón de Cancelación debe ser una cadena de texto.',
            'link_meet.string' => 'El campo Enlace de Encuentro debe ser una cadena de texto.',
            'reference_id.string' => 'El campo ID de Referencia debe ser una cadena de texto.',
            'payment_status.string' => 'El campo Estado de Pago debe ser una cadena de texto.',
            'payment_id.string' => 'El campo ID de Pago debe ser una cadena de texto.',
            'survey_status.string' => 'El campo Estado de Encuesta debe ser una cadena de texto.',
            'rate.numeric' => 'El campo Tasa debe ser numérico.',
            'comment.string' => 'El campo Comentario debe ser una cadena de texto.',
        ]);

        $canceled = isset($validatedData['canceled']) && $validatedData['canceled'] ? true : false;


        //VALIDATE AVAILABILITY
        $response_checkavailability = $this->meetService->CheckAvailability(
            $validatedData['date_meet'],
            (int)$validatedData['specialist_id'],
            (int)$validatedData['service_id'],
            (int)$validatedData['service_option_id']
        );

        if(!$response_checkavailability['status']){
            return redirect()->route('meets.create')
                            ->with('logError', $response_checkavailability['message']);
        } 

        //PRICE IS CALCULATED
        $price_calculated = isset($validatedData['price_calculated']) && $validatedData['price_calculated'] ? true : false;

        $response_calculated_amount = $this->meetService->CalculateAmount(
            (int)$validatedData['service_id'],
            (int)$validatedData['service_option_id'],
            (int)$validatedData['discount_code_id'], 
            (int)$validatedData['discount'],
            (float)$validatedData['price'],
            $price_calculated
        );

        if(!$response_calculated_amount['status']){

            return redirect()->route('meets.create')
                             ->with('logError', $response_calculated_amount['message']);
        }
        $price = $response_calculated_amount['price'];
        $discount = $response_calculated_amount['discount'];
        $discounted_price = $response_calculated_amount['discounted_price'];

        //GENERATE MEET LINK
        $create_link_meet = isset($validatedData['create_link_meet']) && $validatedData['create_link_meet'] ? true : false;

        $response_generate_link = [];
        if($create_link_meet){
            if(!isset($validatedData['link_meet']) || !$validatedData['link_meet']){

                $response_generate_link = $this->meetService->GenerateMeetLink(
                    (int)$validatedData['user_id'], 
                    (int)$validatedData['specialist_id'],
                    $validatedData['date_meet'],
                    (int)$validatedData['duration']
                );

                if($response_generate_link['status']){
                    $validatedData['link_meet'] = $response_generate_link['link_meet']; 
                }else{

                    $return_message = $response_generate_link['message'];

                    $this->logService->Log(4,$return_message);

                    return redirect()->route('meets.create')->with('logError', $return_message);
                }
            }
        }

        $meet = Meet::create([
            'user_id' => $validatedData['user_id'],
            'specialist_id' => $validatedData['specialist_id'],
            'service_id' => $validatedData['service_id'],
            'service_option_id' => $validatedData['service_option_id'],
            'duration' => $validatedData['duration'],
            'discount_code_id' => $validatedData['discount_code_id'] > 0 ? $validatedData['discount_code_id'] : null,
            'date_meet' => $validatedData['date_meet'],
            'price_calculated' => $price_calculated,
            'price' => $price,
            'discount' => $discount,
            'discounted_price' => $discounted_price,
            'canceled' => $canceled,
            'canceled_reason' => $validatedData['canceled_reason'],
            'create_link_meet' => $create_link_meet,
            'link_meet' =>  isset($response_generate_link['link_meet']) ? $response_generate_link['link_meet'] : $validatedData['link_meet'],
            'meeting_id' =>  isset($response_generate_link['meeting_id']) ? $response_generate_link['meeting_id'] : 0,
            'meeting_passwrord' =>  isset($response_generate_link['meeting_password']) ? $response_generate_link['meeting_password'] : '' ,
            'reference_id' => $validatedData['reference_id'],
            'payment_status' => $validatedData['payment_status'],
            'payment_id' => $validatedData['payment_id'],
            'survey_status' => $validatedData['survey_status'],
            'rate' => $validatedData['rate'],
            'comment' => $validatedData['comment'],
        ]);

        if(isset($validatedData['payment_status']) && $validatedData['payment_status'] == Meet::PAYMENT_STATUS_3){
            $meethistory = MeetHistory::create([
                'meet_id' => $meet->id,
            ]);
        }else{
            $create_payment = isset($validatedData['create_payment']) && $validatedData['create_payment'] ? true : false;
            if($create_payment){
                $response_payment = $this->paymentService->makePayment();

                if($response_payment['status']){

                    $meet->create_payment = $create_payment;
                    $meet->reference_id = $response_payment['preference_id']; 
                    $meet->payment_link = $response_payment['init_point'];
                    $meet->save();

                }else{

                    $return_message = $response_payment['payment_message'];
                    $this->logService->Log(4,$return_message);
                    return redirect()->route('meets.create')->with('logError', $return_message);
                }
            }
        }
        

        $return_message = 'Reunion creada exitosamente.';

        $this->logService->Log(1,$return_message);

        return redirect()->route('meets.index')->with('logSuccess', $return_message);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $meet = Meet::where('id', $id)->first();
        return view('meets.show', compact('meet'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Meet $meet)
    {
        return view('meets.edit', compact('meet'))->with('editMode', true);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meet $meet)
    {

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialist_id' => 'required|exists:specialists,id',
            'service_id' => 'required|exists:services,id',
            'service_option_id' => 'required',
            'duration'=> 'required|numeric',
            'discount_code_id' => 'nullable',
            'date_meet' => 'required|date_format:Y-m-d\TH:i',
            'price_calculated' => 'nullable',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric',
            'discounted_price' => 'required|numeric',
            'canceled' => 'nullable',
            'canceled_reason' => 'nullable|string',
            'create_link_meet'=>'nullable',
            'link_meet' => 'nullable|string',
            'create_payment' => 'nullable',
            'reference_id' => 'nullable|string',
            'payment_status' => 'nullable|string',
            'payment_id' => 'nullable|string',
            'survey_status' => 'nullable|string',
            'rate' => 'nullable|numeric',
            'comment' => 'nullable|string',
        ], [
            'user_id.required' => 'El campo Usuario es obligatorio.',
            'user_id.exists' => 'El usuario seleccionado no es válido.',
            'specialist_id.required' => 'El campo Especialista es obligatorio.',
            'specialist_id.exists' => 'El especialista seleccionado no es válido.',
            'service_id.required' => 'El campo Servicio es obligatorio.',
            'service_id.exists' => 'El servicio seleccionado no es válido.',
            'service_option_id.required' => 'La opcion del  servicio es obligatorio.',
            'duration.required' => 'La duración del meet es obligatorio.',
            'duration.numeric' => 'El campo debe ser numérico.',
            'date_meet.required' => 'El campo Fecha de Encuentro es obligatorio.',
            'date_meet.date_format' => 'El campo Fecha de Encuentro debe ser una fecha y hora válida.',
            'price.numeric' => 'El campo Precio debe ser numérico.',
            'price.required' => 'El campo Precio debe es obligatorio.',
            'discount.numeric' => 'El campo Descuento debe ser numérico.',
            'discounted_price.numeric' => 'El campo Precio Descontado debe ser numérico.',
            'discounted_price.required' => 'El campo Precio Descontado es obligatorio.',
            'canceled_reason.string' => 'El campo Razón de Cancelación debe ser una cadena de texto.',
            'link_meet.string' => 'El campo Enlace de Encuentro debe ser una cadena de texto.',
            'reference_id.string' => 'El campo ID de Referencia debe ser una cadena de texto.',
            'payment_status.string' => 'El campo Estado de Pago debe ser una cadena de texto.',
            'payment_id.string' => 'El campo ID de Pago debe ser una cadena de texto.',
            'survey_status.string' => 'El campo Estado de Encuesta debe ser una cadena de texto.',
            'rate.numeric' => 'El campo Tasa debe ser numérico.',
            'comment.string' => 'El campo Comentario debe ser una cadena de texto.',
        ]);

        $canceled = isset($validatedData['canceled']) && $validatedData['canceled'] ? true : false;


        //VALIDATE AVAILABILITY
        $new_dateMeet = Carbon::parse($validatedData['date_meet'])->format('Y-m-d H:i:s');
        $existing_dateMeet = Carbon::parse($meet->date_meet)->format('Y-m-d H:i:s');

        if($existing_dateMeet != $new_dateMeet){

            $response_checkavailability = $this->meetService->CheckAvailability(
                $validatedData['date_meet'],
                (int)$validatedData['specialist_id'],
                (int)$validatedData['service_id'],
                (int)$validatedData['service_option_id']
            );

            if(!$response_checkavailability['status']){
                return redirect()->route('meets.edit', $meet)
                                ->with('logError', $response_checkavailability['message']);
            } 
        }


        //PRICE IS CALCULATED
        $price_calculated = isset($validatedData['price_calculated']) && $validatedData['price_calculated'] ? true : false;

        $response_calculated_amount = $this->meetService->CalculateAmount(
            (int)$validatedData['service_id'],
            (int)$validatedData['service_option_id'],
            (int)$validatedData['discount_code_id'], 
            (int)$validatedData['discount'],
            (float)$validatedData['price'],
            $price_calculated
        );

        if(!$response_calculated_amount['status']){

            return redirect()->route('meets.edit',$meet)
                             ->with('logError', $response_calculated_amount['message']);
        }
        $price = $response_calculated_amount['price'];
        $discount = $response_calculated_amount['discount'];
        $discounted_price = $response_calculated_amount['discounted_price'];

        //GENERATE MEET LINK
        $create_link_meet = isset($validatedData['create_link_meet']) && $validatedData['create_link_meet'] ? true : false;

        $response_generate_link = [];
        if($create_link_meet){
            if(!isset($validatedData['link_meet']) || !$validatedData['link_meet']){

                $response_generate_link = $this->meetService->GenerateMeetLink(
                    (int)$validatedData['user_id'], 
                    (int)$validatedData['specialist_id'],
                    $validatedData['date_meet'],
                    (int)$validatedData['duration']
                );

                if($response_generate_link['status']){

                    $validatedData['link_meet'] = $response_generate_link['link_meet']; 

                }else{

                    $return_message = $response_generate_link['message'];

                    $this->logService->Log(4,$return_message);

                    return redirect()->route('meets.edit',$meet)->with('logError', $return_message);
                }
            }
        }

        $meet->update([
            'user_id' => $validatedData['user_id'],
            'specialist_id' => $validatedData['specialist_id'],
            'service_id' => $validatedData['service_id'],
            'service_option_id' => $validatedData['service_option_id'],
            'duration' => $validatedData['duration'],
            'discount_code_id' =>  $validatedData['discount_code_id'] > 0 ? $validatedData['discount_code_id'] : null,
            'date_meet' => $validatedData['date_meet'],
            'price_calculated' => $price_calculated,
            'price' => $price,
            'discount' => $discount,
            'discounted_price' => $discounted_price,
            'canceled' => $canceled,
            'canceled_reason' => $validatedData['canceled_reason'],
            'create_link_meet' => $create_link_meet,
            'link_meet' =>  isset($response_generate_link['link_meet']) ? $response_generate_link['link_meet'] : $validatedData['link_meet'],
            'meeting_id' =>  isset($response_generate_link['meeting_id']) ? $response_generate_link['meeting_id'] : 0,
            'meeting_passwrord' =>  isset($response_generate_link['meeting_password']) ? $response_generate_link['meeting_password'] : '' ,
            'reference_id' => $validatedData['reference_id'],
            'payment_status' => $validatedData['payment_status'],
            'payment_id' => $validatedData['payment_id'],
            'survey_status' => $validatedData['survey_status'],
            'rate' => $validatedData['rate'],
            'comment' => $validatedData['comment'],
        ]);

        if(isset($validatedData['payment_status']) && $validatedData['payment_status'] == Meet::PAYMENT_STATUS_3){
            $meethistory = MeetHistory::create([
                'meet_id' => $meet->id,
            ]);
        }else{
            $meetHistorycreated = MeetHistory::where('meet_id',$meet->id)->first();
            if($meetHistorycreated){
                $meetHistorycreated->delete();
            }

            $create_payment = isset($validatedData['create_payment']) && $validatedData['create_payment'] ? true : false;
            if($create_payment){
                $response_payment = $this->paymentService->makePayment();

                if($response_payment['status']){

                    $meet->create_payment = $create_payment;
                    $meet->reference_id = $response_payment['preference_id']; 
                    $meet->payment_link = $response_payment['init_point'];
                    $meet->save();

                }else{

                    $return_message = $response_payment['payment_message'];
                    $this->logService->Log(4,$return_message);
                    return redirect()->route('meets.edit')->with('logError', $return_message);
                }
            }
        }


        $return_message = 'Reunion actualizada exitosamente.';

        $this->logService->Log(1,$return_message);

        return redirect()->route('meets.index')->with('logSuccess', $return_message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meet $meet)
    {

        $meetHistorycreated = MeetHistory::where('meet_id',$meet->id)->first();
        if($meetHistorycreated){
            $meetHistorycreated->delete();
        }
        // Delete the specified Category
        $meet->delete();
        // Return a success response or redirect as desired
        $return_message = 'Reunion borrada exitosamente.';

        $this->logService->Log(1,$return_message);

        return redirect()->route('meets.index')->with('logSuccess', $return_message);
    }

    public function notifyUser(Meet $meet){

        try{

            $notifymeet_user        =  new NotifyMeet($meet->user->name, $meet->date_meet , $meet->link_meet);
            Mail::to($meet->user->email)->send($notifymeet_user);

        }catch (\Exception $e){

            $return_message = 'Hubo un error notificando al usuario, intenta en un rato.';

            $this->logService->Log(4,$return_message);

            return redirect()->route('meets.index')->with('logError', $return_message);
        }

        return redirect()->route('meets.index')->with('logSuccess', 'El usuario han sido notificados exitosamente.');
    }


    public function notifySpecialist(Meet $meet){

        try{

            $notifymeet_specialist  =  new NotifyMeet($meet->specialist->user->name, $meet->date_meet , $meet->link_meet);
            Mail::to($meet->specialist->user->email)->send($notifymeet_specialist);

        }catch(\Exception $e){

            $return_message = 'Hubo un error notificando al especialista, intenta en un rato.';

            $this->logService->Log(4,$return_message);

            return redirect()->route('meets.index')->with('logError', $return_message);
        }

        return redirect()->route('meets.index')->with('logSuccess', 'El especialista han sido notificados exitosamente.');

    }

    public function getPaymentStatus(Meet $meet){
        $response_validatepayment = $this->paymentService->validatePayment($meet->payment_id);

        if($response_validatepayment['status']){

            $return_message = $response_validatepayment['payment_message'];

            $this->logService->Log(1,$return_message);

            return redirect()->route('meets.index')->with('logSuccess', $return_message);

        }else{

            $return_message = $response_validatepayment['payment_message'];

            $this->logService->Log(4,$return_message);

            return redirect()->route('meets.index')->with('logError',$return_message);
        }
    }

    public function GetMeetsData(Request $request){


        $start_date = $request->query('start_date');
        $end_date = $request->query('end_date');

        // Get the requested page from the query string
        $startDate = Carbon::createFromFormat('Y-m-d', $start_date)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $end_date)->endOfDay();

        $meetsQuery = Meet::whereBetween('date_meet', [$startDate, $endDate]);

        // For example, you can retrieve the meets within the specified date range:
        $meets = $meetsQuery->get();

        $csvFileName = 'meets.csv';

        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $handle = fopen('php://output', 'w');
        fputcsv($handle, array_keys($meets->first()->toArray()));

        foreach ($meets as $meet) {
            fputcsv($handle, $meet->toArray());
        }

        fclose($handle);

        // Create the response with the correct content
        $responseContent = ob_get_clean(); // Get the output buffer content

        return new Response(
            $responseContent,
            200,
            $headers
        );
    }

}
