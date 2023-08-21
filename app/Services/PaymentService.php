<?php
namespace App\Services;

use App\Models\Meet;
use App\Models\User;
use App\Models\Specialist;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;



class PaymentService
{

    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(env('MP_PROD_ACCESS_TOKEN'));
    }


    public function callBackPayment(string $preference_id, string $payment_id){
        $meet = Meet::where('preference_id', $preference_id);
        $meet->payment_id = $payment_id;
        $meet->update();
    }

    public function makePayment(
        Meet $meet,
        string $title, 
        string $description, 
    ){

        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();
        // Crea un ítem en la preferencia
        $item = new MercadoPago\Item();
        $item->id = $meet->service_id;
        $item->title = $title;
        $item->description = $description;
        $item->currency_id = 'PEN';
        $item->quantity = 1;
        $item->unit_price = $meet->discounted_price;
        $preference->items = array($item);

        // Create payer details
        $payer = new MercadoPago\Payer();
        $payer->name = $meet->user->name;
        $payer->email = $meet->user->email;

        // Set payer information in the preference
        $preference->payer = $payer;

        // Set back URLs and other preferences
        $back_urls = new stdClass();
        $back_urls->success = env('BACKEND_URL') . 'confirmationpayment?meetID=' . $meet->id;
        $back_urls->failure = env('BACKEND_URL') . 'confirmationpayment?meetID=' . $meet->id;
        $back_urls->pending = env('BACKEND_URL') . 'confirmationpayment?meetID=' . $meet->id;
        $preference->back_urls = $back_urls;
        $preference->auto_return = "approved";
        $preference->binary_mode = true;

        // Save the preference
        try{
            $preference->save();

            return [
                'status' => true,
                'preference_id' => $preference->id,
                'init_point' => $preference->init_point,
                //'init_point' => $preference->sandbox_init_point,
                'payment_message' => 'Preferecian creada exitosamente',
            ];
        }catch(\Exception $e){

            return [
                'status' => false,
                'preference_id' => "",
                'init_point' => "",
                'payment_message' => $e->getMessage(),
            ];
        }
    }

    public function  cancelPayment(int $payment_id){

        try{
            $payment = MercadoPago\Payment::find_by_id($payment_id);
            $payment->status = "cancelled";
            $payment->update();

            return [
                'status' => true,
                'payment_status' => $payment->status,
                'payment_message' => 'El pago se ha cancelado correctamente.',
            ];

        }catch(\Exception $e){
            return [
                'status' => false,
                'payment_status' => '',
                'payment_message' => $e->getMessage(),
            ];

        }

    }

    public function validatePayment(string $payment_id ){

        if(!$payment_id){
            return [
                'status' => false,
                'payment_status' => '',
                'payment_message' => 'Ingresa un id de pago.',
            ];
        }

        try{
            $payment = MercadoPago\Payment::find_by_id($payment_id);
            return [
                'status' => true,
                'payment_status' => $payment->status,
                'payment_message' => 'El estado del pago es' . $payment->status,
            ];

        }catch(\Exception $e){
            return [
                'status' => false,
                'payment_status' => '',
                'payment_message' => $e->getMessage(),
            ];

        }

    }

}


