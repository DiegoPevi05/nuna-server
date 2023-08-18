<?php
namespace App\Services;
use Illuminate\Support\Facades\Auth;
use App\Models\LogModel;
use App\Models\User;

class LogService
{

    public function Log(int $type, string $message){

        $user = Auth::user();

        if($user->role == User::ROLE_USER){
            return ;
        } 

        $type_string = null;

        if($type == 1){

            $type_string = 'SUCCESS';

        }elseif($type == 2){

            $type_string = 'WARNING';

        }elseif($type == 3){

            $type_string = 'INFO';

        }elseif($type == 4){

            $type_string = 'ERROR';
        }else{

            $type_string = 'ERROR';
        }

        $log = LogModel::create([
            'user_id' => $user ? $user->id :null,
            'user_role' => $user ? $user->role : null, 
            'user_name' =>  $user ? $user->name : null,
            'type' => $type_string,
            'message' => $message
        ]);

    }
}
