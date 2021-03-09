<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class JwtAuth{

  public $key;
  public function __construct()
  {
      $this->key = 'clave_secreta-031097';
  }
  
  public function signup($email, $password)
  {
    //verify user
      $user = User::where([
            'email' => $email,
            'password' => $password
      ])->first();

      $signup = false;
      if(is_object($user))
      {
          $signup = true;
      }
    //generate token
      if($signup)
      {
        $token = array(
          'sub'  =>  $user->id,
          'email'  =>  $user->email,
          'name'  =>  $user->name,
          'surname'  =>  $user->surname,
          'role' =>$user->role,
          'iat'  =>  time(),
          'exp'  =>  time()+(7*120)
        );
        //data
        $data = JWT::encode($token, $this->key, 'HS256'); 

      } else {

        $data = array(
          'code' => 400,
          'status' => 'error',
          'message' => 'Login failed!'
        );
      }
    return $data;
  }  
}