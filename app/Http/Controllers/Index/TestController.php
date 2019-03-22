<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class TestController extends Controller
{
    public function login(Request $request){
     //   echo 111;
         //   exit;
          $name = $request->input('u_name');
            $password=$request->input('u_pwd');
            $data = [
                'u_name'    =>  $name,
                'u_pwd'     =>  $password
            ];
            //$url = 'http://passport.lara.com/api/login';
            $url = 'http://passport.hz4155.cn/api/login';
            $ch = curl_init($url);
            curl_setopt($ch,CURLOPT_HEADER,0);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_POST,1);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
            $response = curl_exec($ch);
            curl_close($ch);
            $response = json_decode($response,true);


            return $response;



        }
    }