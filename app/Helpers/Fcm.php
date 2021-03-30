<?php

namespace App\Helpers;

class Fcm {

    private $key = 'AAAAp_9IOHo:APA91bGzmbnOVYqeVc-mDOdrdmkhonudPW2DStEabPknx-FXOcVu2bNiOABOK8BwXCK-HVML4Vi6zOVMvXoL5-7Y_M1QlLkE4IqFJSwVKexIZXv1kI614gk_FrsHIsIcBszzP5JcXD5l';


    public function send($token, $notification, $device_type) {
      
        $data = array('priority' => 'high', 'content_available' => true, 'vibrate' => 1, 'sound' => 1, 'alert' => 1);
        foreach ($notification as $key => $value) {
            $data[$key] = $value;
        }
        $params=array();
        if(is_array($token)){
            $params['registration_ids']=$token;
        }else{
            $params['to']=$token;
        }
        if ($device_type == 'ios') {
            $params['notification'] = $data;
        } else if ($device_type == 'and') {
            $params['data'] = $data;
        } else if ($device_type == 'twice') {
            $params = array(
                'to' => $token,
                'notification' => $data,
                'data' => $data,
            );
            
        }
        
        return $this->request(json_encode($params));
    }

   

    private function request($params) {
       
        $headers = array(
            'Authorization: key=' . $this->key,
            'Content-Type: application/json;'
        );

        $returned = true;

        $url = 'https://fcm.googleapis.com/fcm/send';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,  $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $result = curl_exec($ch);
        //dd($result);
        if ($result === False) {
            $returned = false;
            die('Curl Filed ' . curl_errno($ch));
        }
        curl_close($ch);
        return $result;
    }

    

}

