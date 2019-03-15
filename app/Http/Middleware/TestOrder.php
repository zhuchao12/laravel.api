<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
class TestOrder
{
    public function handle($request, Closure $next){
        // print_r($_SERVER);
        $url = $_SERVER['REQUEST_URI'];
        //echo $url;
        $url_hash = substr(md5($url),0,10);
        //  echo $url_hash;
        $ip = $_SERVER['REMOTE_ADDR'];
        //  echo $ip;
        $redis_key = 'str:'.$url_hash . ':' .$ip;
        //    echo $redis_key;
        $arr = Redis::incr($redis_key);
        Redis::expire($redis_key,60);
        //   var_dump($num);
        if($arr>5){
            //非法请求
            $response = [
                'errno' => '40003',
                'msg' => 'no'
            ];
            Redis::expire($redis_key,5);
            $redis_invalid_ip = 's:invalid:ip';
            Redis::sAdd($redis_invalid_ip,$ip);
            return json_encode($response);
        }
        return $next($request);

    }
}