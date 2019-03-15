<?php

namespace App\Http\Controllers\Index;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class UserController extends Controller
{

    public $hash_token = 'user:login:';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Request $request
     * 用户登录
     */
    public function login(Request $request)
    {
        $uid = $request->input('uid');
        //生成token
        $token = substr(md5(time() + $uid + rand(1000,9999)),10,20);
        if(1){
            $key = $this->hash_token.$uid;
            //echo $key;exit;
            Redis::hSet($key,'token',$token);
            //Redis::hSet($key,$token);
            Redis::expire($key,60*24*7);

            $response = [
                'errno'     =>  0,
                'token'     =>  $token
            ];
        }else{
            //TODO
        }
        return $response;
    }

    public function uCenter(Request $request)
    {
        $uid = $request->input('uid');
        //print_r($_SERVER);exit;

        if(!empty($_SERVER['HTTP_TOKEN'])){
            $http_token = $_SERVER['HTTP_TOKEN'];
            $key = $this->hash_token . $uid;

            $token = Redis::hGet($key,'token');

            if($token == $http_token){
                $response = [
                    'errno'     =>  0,
                    'msg'       =>  'ok'
                ];
            }else{
                $response = [
                    'errno'     =>  50001,
                    'msg'       =>  'invalid token'
                ];
            }

        }else{
            $response = [
                'errno'     =>  50000,
                'msg'       =>  'not find token'
            ];
        }
        return $response;
    }


    public function test(){
            echo 'aaaaaa';
    }
}
