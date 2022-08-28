<?php
namespace App\Http\Helpers;

class APIHelpers{
    public static function createResponse($status,  $message, $data){
        $result=[];
        if($status==false){
            $result['status'] = false;
            $result['message'] = $message;
            $result['data'] = $data;
        }else{
            $result['status'] = true;
            $result['message'] = $message;
            $result['data'] = $data;
        }
        return $result;
    }
}
