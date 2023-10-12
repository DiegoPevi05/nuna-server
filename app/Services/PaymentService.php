<?php
namespace App\Services;

use App\Models\Meet;
use App\Models\User;
use App\Models\Specialist;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


function generateExternalReference(Meet $meet) {
    $currentDate = date('Y-m-d');
    $externalReference = $meet->id . '-' . $meet->service_id . '-' . $currentDate;
    return $externalReference;
}


class PaymentService
{

    public function makePayment(
        Meet $meet,
        string $title, 
        string $description, 
    ){

        try{
            $url = 'https://api.mercadopago.com/checkout/preferences';
            $accessToken = env('MP_ACCESS_TOKEN'); 

            $externalReference = generateExternalReference($meet);

            $data = [
                "items" => [
                    [
                        "id" => $meet->service_id,
                        "title" => $title,
                        "description" => $description,
                        "currency_id" => "PEN",
                        "quantity" => 1,
                        "unit_price" => (float) $meet->discounted_price,
                    ]
                ],
                "payer" => [
                    "name" => $meet->user->name,
                    "email" => $meet->user->email
                ],
                "back_urls" => [
                    "success" => env('BACKEND_URL') . 'confirmationpayment?meetID=' . $meet->id,
                    "failure" => env('BACKEND_URL') . 'confirmationpayment?meetID=' . $meet->id,
                    "pending" => env('BACKEND_URL') . 'confirmationpayment?meetID=' . $meet->id
                ],
                "auto_return" => "approved",
                "external_reference" => $externalReference,
                "binary_mode" => true,
            ];

            $jsonData = json_encode($data);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response === false) {
                return [
                    'status' => false,
                    'preference_id' => "",
                    'init_point' => "",
                    'external_reference' => "",
                    'payment_message' => "Error in the request",
                ];
            } else {
                $responseData = json_decode($response, true);

                if (isset($responseData['status']) && $responseData['status'] === 400) {
                    return [
                        'status' => false,
                        'preference_id' => "",
                        'init_point' => "",
                        'external_reference' => "",
                        'payment_message' => isset($responseData['message']) ? $responseData['message'] : "Error in the response data",
                    ];
                } else {
                    return [
                        'status' => true,
                        'preference_id' => $responseData['id'],
                        'init_point' => $responseData['init_point'], // DEV
                        //'init_point' => $responseData['sandbox_init_point'], // PRD
                        'external_reference' => $responseData['external_reference'], 
                        'payment_message' => 'Preference created successfully',
                    ];
                }
            }

        }catch(\Exception $e){



            return [
                'status' => false,
                'preference_id' => "",
                'init_point' => "",
                'external_reference' => "",
                'payment_message' => $e->getMessage(),
            ];
        }
    }

    public function validatePayment(Meet $meet) {

        if (!$meet->external_reference) {
            return [
                'status' => false,
                'payment_status' => '',
                'payment_id' => '',
                'payment_message' => 'El meet no tiene external reference revisa en tu panel de mercado pago.',
            ];
        }

        try {
            $accessToken = env('MP_ACCESS_TOKEN');
            $url = 'https://api.mercadopago.com/v1/payments/search?' . 
                   'sort=date_created&criteria=desc&' . 
                   'external_reference=' . $meet->external_reference . '&' .
                   'range=date_created&begin_date=NOW-30DAYS&end_date=NOW';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            if ($response === false) {
                return [
                    'status' => false,
                    'payment_status' => '',
                    'payment_id' => '',
                    'payment_message' => "Error in the request",
                ];
            } else {
                $responseData = json_decode($response, true);

                \Log::info('Response Data: ' . print_r($responseData, true));

                if (isset($responseData['status']) && $responseData['status'] === 400) {
                    return [
                        'status' => false,
                        'payment_status' => '',
                        'payment_id' => '',
                        'payment_message' => isset($responseData['message']) ? $responseData['message'] : "Error in the response data 400",
                    ];
                }else if(isset($responseData['status']) && $responseData['status'] === 404){
                    return [
                        'status' => false,
                        'payment_status' => 'No Payments Found 404',
                        'payment_id' => '',
                        'payment_message' => isset($responseData['message']) ? $responseData['message'] : "No payments found 404",
                    ];
                }else if (count($responseData['results']) > 0) {
                    $firstPayment = $responseData['results'][0];

                    return [
                        'status' => true,
                        'payment_status' => $firstPayment['status'],
                        'payment_id' => $firstPayment['id'],
                        'payment_message' => 'El estado del pago es ' . $firstPayment['status'],
                    ];
                } else {
                    return [
                        'status' => false,
                        'payment_status' => 'No hay pagos encontrados',
                        'payment_id' => '',
                        'payment_message' => 'No hay pagos encontrados',
                    ];
                }
            }
        } catch (\Exception $e) {

            return [
                'status' => false,
                'payment_status' => '',
                'payment_id' => '',
                'payment_message' => $e->getMessage(),
            ];
        }
    }

}


