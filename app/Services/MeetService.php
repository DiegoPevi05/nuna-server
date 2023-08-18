<?php
namespace App\Services;

use App\Models\DiscountCode;
use App\Models\SpecialistTimes;
use App\Models\Meet;
use App\Models\User;
use App\Models\Specialist;
use App\Models\Service;
use App\Models\TokenZoom;
use Carbon\Carbon;
use GuzzleHttp\Client as GuzzleHttpClient;


class MeetService
{
    public function CalculateAmount(int $service_id,int $service_option_id, int $discount_code_id, int $discount, float $price, bool $price_calculated){


        if(!$price_calculated){
            return [
                'status' => true,
                'message' => 'Precio no calculado.',
                'price' => $price,
                'dicount' => $discount > 0 ? $discount : 0,
                'discounted_price' => $price - ($price * $discount /100),
            ];
        }

        if($discount_code_id > 0){
            $discount_code_db = DiscountCode::where('id', $discount_code_id)->first();

            if(!$discount_code_db){
                return [
                    'status' => false,
                    'message' => 'El codigo de descuento no existe.',
                ];
            }

            if($discount_code_db->quantity_discounts == 0){
                return [
                    'status' => false,
                    'message' => 'El descuento aplicado ya no se puede aplicar valido.',
                ];
            }

            if($discount_code_db->status == 'inactive'){
                return [
                    'status' => false,
                    'message' => 'El descuento aplicado no ya no es valido.',
                ];
            }

            $now = Carbon::now();
            if($discount_code_db->expired_date < $now){
                return [
                    'status' => false,
                    'message' => 'El descuento aplicado ya vencio.',
                ];
            }
        }

        //GET SERVICE PRICE
        $service            = Service::where('id', $service_id)->first();
        $options_service    = json_decode($service->options);

        $price_service      = 0;

        foreach($options_service as $option){
            if($option->id == $service_option_id){
                $price_service = $option->price; 
            }
        }

        if($price_service == 0){
            return [
                'status' => false,
                'message' => 'La opción del servicio no es valida.',
            ];

        }

        $discounted_price = $discount_code_id > 0 ?  $price_service - ($price_service * $discount_code_db->discount /100) : $price_service; 

        return [
            'status' => true,
            'message' => 'Precio es valido.',
            'price' => $price_service,
            'discount' => $discount_code_id > 0 ? $discount_code_db->discount : 0,
            'discounted_price' => $discounted_price,
        ];

    }

    public function CheckAvailability(string $date_meet, int $specialist_id, int $service_id, int $service_option_id){

        $dateMeet = Carbon::parse($date_meet)->format('Y-m-d H:i');
        $dateMeet = Carbon::parse($date_meet)->setSeconds(0);

        //GET SERVICE TIME
        $service            = Service::where('id', $service_id)->first();
        $options_service    = json_decode($service->options);

        $time_duration      = 0;

        foreach($options_service as $option){
            if($option->id == $service_option_id){
                $time_duration = $option->duration; 
            }
        }

        if($time_duration == 0){
            return [
                'status' => false,
                'message' => 'Opcion del servicio no encontrada.',
            ];
        }

        //ADD THE MINUTES TO SET THE END_SEARCH_DATETIME
        $start_search_datetime = $dateMeet;
        $end_search_datetime = $dateMeet->copy()->addMinutes($time_duration);

        $specialistTimes = SpecialistTimes::where('specialist_id', $specialist_id)
        ->where(function ($query) use ($start_search_datetime, $end_search_datetime) {
            $query->where('start_date', '<=', $start_search_datetime)
                ->where('end_date', '>=', $end_search_datetime);
        })
        ->get();

        // Validate if there are any matching specialistTimes
        if ($specialistTimes->isEmpty()) {
            return [
                'status' => false,
                'message' => 'No hay horarios para el especialista seleccionado.',
            ];
        }


        $existedMeets = Meet::where('specialist_id', $specialist_id)
                ->whereDate('date_meet', $dateMeet)
                ->get();

        if($existedMeets->isEmpty()){
            return [
                'status' => true,
                'message' => 'Hay disponibilidad en el dia.',
            ];

        }

        foreach ($existedMeets as $existedMeet) {
            $existingStart = Carbon::parse($existedMeet->date_meet);
            $existingEnd = $existingStart->copy()->addMinutes($existedMeet->duration);

            if (
                ($start_search_datetime >= $existingStart && $start_search_datetime < $existingEnd) ||
                ($end_search_datetime > $existingStart && $end_search_datetime <= $existingEnd)
            ) {
                return [
                    'status' => false,
                    'message' => 'Ya existe una reunión programada en este horario.',
                ];
            }
        }

        return [
            'status' => true,
            'message' => 'Hay disponibilidad en el día.',
        ];

    }


    public function GenerateMeetLink(int $user_id, int $specialist_id, string $date_meet, int $minutes){

        $tokenDb = TokenZoom::orderBy('created_at', 'desc')->first();
        $user = User::where('id',$user_id)->first();
        $specialist = Specialist::where('id',$specialist_id)->first();
        $user_specialist = $specialist->user;

        if(!$tokenDb){
            return [
                'status' => false,
                'link_meet' => '',
                'password_meeting' => '',
                'message' => 'Crea las credenciales de zoom o actualizalas y luego genera el token.'
            ];
        }

        $arrToken = json_decode($tokenDb->access_token,true); 
        $accessToken = $arrToken['access_token']; 
        $randomPassword = strval(rand(100000, 999999));
        $formattedDateMeet = date("Y-m-d\TH:i:s\Z", strtotime($date_meet));

        try{
            $client = new GuzzleHttpClient(['base_uri' => 'https://api.zoom.us']);
            $response = $client->request('POST', '/v2/users/me/meetings', [
                "headers" => [
                    "Authorization" => "Bearer " . $accessToken,
                    "Content-Type" => "application/json"
                ],
                'json' => [
                    "topic" => "Nuna inivitacion a reunion",
                    "type" => 2,
                    "start_time" => $formattedDateMeet,
                    "duration" => $minutes > 40 ? 40 : $minutes,
                    "password" => $randomPassword,
                    "timezone" => "America/Lima",
                    "settings" => [
                        "jbh_time" => 0,
                        "join_before_host" => true,
                        "contact_email" =>  "administracion@nuna.com.pe",
                        "meeting_authentication" => false,
                        "meeting_invitees" => [
                            [
                                "email" => $user->email,
                            ],
                            [
                                "email" => $user_specialist->email,
                            ]
                        ],
                        "participant_video" =>  true,
                        "show_share_button" =>  true,
                        "waiting_room" => false
                    ]
                ],
            ]);
      
            $data = json_decode($response->getBody());

            return [
                'status' => true,
                'id_meeting' => $data->id,
                'link_meet' => $data->join_url,
                'password_meeting' => $data->password,
                'message' => ''
            ];

        }catch(\Exception $e){
            if( 401 == $e->getCode()) {
                $refresh_token = $arrToken['refresh_token'];
      
                $client = new GuzzleHttpClient(['base_uri' => 'https://zoom.us']);
                $response = $client->request('POST', '/oauth/token', [
                    "headers" => [
                        "Authorization" => "Basic ". base64_encode(env('CLIENT_ID_ZOOM').':'.env('CLIENT_SECRET_ZOOM'))
                    ],
                    'form_params' => [
                        "grant_type" => "refresh_token",
                        "refresh_token" => $refresh_token
                    ],
                ]);

                $tokenDb->update([
                    'access_token' => json_encode($response->getBody())
                ]);
      
                $this->GenerateMeetLink($user_id, $specialist_id, $date_meet,$minutes);
            } else {

                return [
                    'status' => false,
                    'id_meeting' => 0,
                    'link_meet' => '',
                    'password_meeting' => '',
                    'message' => $e->getMessage()
                ];
            }
            
            return [
                'status' => false,
                'id_meeting' => 0,
                'link_meet' => '',
                'password_meeting' => '',
                'message' => $e->getMessage()
            ];
        }

    }

    /*public function DeleteMeeting(int MeetingID){
        return true;
    }

    public function makePayment(float discounted_price){
        return true;
    }*/

    public function validatePayment(string $reference_id, string $payment_status, string $payment_id ){

        try{
            if(1==1){
                return [
                    'status' => true,
                    'payment_status' => '',
                    'payment_message' => 'El pago se ha validado se encuentra exitoso',
                ];
            }

        }catch(\Exception $e){
            return [
                'status' => true,
                'payment_status' => '',
                'payment_message' => 'El pago no se ha validadr, intenta en un rato',
            ];

        }

    }

}

