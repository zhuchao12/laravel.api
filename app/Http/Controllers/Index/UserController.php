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

    public function aaa(){

        $url = 'https://www.baidu.com';
        $ch = curl_init($url);
        $re = curl_exec($ch);
        $s = file_put_contents('a.html',$re);
        var_dump($s);
    }

    public function bbb(){
        echo PHP_INT_SIZE;echo '<hr>';
        echo PHP_INT_MAX; echo '</br>';
        echo PHP_INT_MIN;  echo '</br>'; echo '<hr>';

      $str = "hollow word";
      $keys = "pass";
      $iv = mt_rand(11111,99999).'asdasdasdaa';
      $enc_str = openssl_encrypt($str,'AES-128-CBC',$keys,OPENSSL_RAW_DATA,$iv);
      var_dump($enc_str);
      //$dec_str = openssl_decrypt($str,'AES-128-CBC',$keys,OPENSSL_RAW_DATA,$iv);
     // var_dump($dec_str);
    }

    public function ccc(){
        $now = time();

        $url = 'http://shop_laravel123.com/test/ccc?t='.$now;

        $data = [
            'name' => 'zhangsan',
            'password' => '123456',
            'email' => '479346874@qq.com',
        ];

        $method = 'AES-128-CBC';

        $now = time();
        $key = 'password';
        $salt = 'qweqwe';
        $iv = substr(md5($now.$salt),5,16);            //加密向量
        $json_str = json_encode($data);
        $enc_data = openssl_encrypt($json_str,$method,$key,OPENSSL_RAW_DATA,$iv);
        $post_data = base64_encode($enc_data);
       // var_dump($post_data) ;

        //向客户端发送数据

        $ch =  curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);

        curl_setopt($ch,CURLOPT_POST,1);

        curl_setopt($ch,CURLOPT_POSTFIELDS,['data'=>$post_data]);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        curl_setopt($ch,CURLOPT_HEADER,0);

        $rs = curl_exec($ch);

      //  echo $rs ;die;

        $response = json_decode($rs,true);

       echo '<pre>'; print_r($response);echo '</pre>'; echo '<hr>';

        $iv2 = substr(md5($response['t'] . $salt),5,16);

        $dec_data = openssl_decrypt(base64_decode($response['data']),$method,$key,OPENSSL_RAW_DATA,$iv2);
        var_dump($dec_data);

    }

    public function ddd(){
       // $now = time();
        $url = 'http://shop_laravel123.com/test/ddd';
            $data = [
                'aaa' => 'aaa',
                'bbb' => 'bbb',
                'ccc' => 'ccc'
            ];
        $method = 'AES-128-CBC';
        $now = time();
        $key = 'password';
        $salt = 'zzzzzz';
        $iv = substr(md5($now,$salt),0,16);
        $json_str = json_encode($data);
        $enc_data = openssl_encrypt($json_str,$method,$key,OPENSSL_RAW_DATA,$iv);
        $post_data = base64_encode($enc_data);

        //计算签名
        $priKey = file_get_contents('./keys/priv.key');
        $res = openssl_get_privatekey($priKey);
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        openssl_sign($post_data, $sign, $res, OPENSSL_ALGO_SHA256);
        $sign = base64_encode($sign);

        $ch =  curl_init();

        curl_setopt($ch,CURLOPT_URL,$url);

        curl_setopt($ch,CURLOPT_POST,1);

        curl_setopt($ch,CURLOPT_POSTFIELDS,['data'=>$post_data,'sign' => $sign,'now' => $now]);

        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        curl_setopt($ch,CURLOPT_HEADER,0);

        $rs = curl_exec($ch);

        var_dump($rs);







    }
}
