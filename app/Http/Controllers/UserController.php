<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Helpers\JwtAuth;



class UserController extends Controller
{
    // public function __construct(){
    //     $this->middleware('api.auth', ['except' => ['index', 'show','login','store','getImage']]);
    // }

    public function login(Request $request){
    
        //Recibir datos por POST
        $json = $request->input('json', null);

        $params = json_decode($json);
        
        $params_array = json_decode($json, true);
      
        //Validar esos datos
        $validate = Validator::make($params_array, [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        if($validate->fails())
        {
            //Validacion ha fallado
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'The user could not be identified!',
                'error' => $validate->errors()
            );
        }
        else
        {
            //hash password
            $pwd = hash('sha256', $params->password);

            //class
            $jwtAuth = new JwtAuth();
        
            //data
            $signup = $jwtAuth->signup($params->email, $pwd);

        }
        
          return response()->json($signup, 200);
    }

}
