<?php

namespace App\Helpers;


Class SMSGateWay {
    
    
    private $url    = "https://apps.gateway.sa/";
    private $user  = "";
    private $password = '';
    private $sid = '';

    public function send($to, $verification_code)
    {
        $data = [
            'user' => $this->user,
            'password' => $this->password,
            'msisdn' => $to,
            'sid' => $this->sid,
            'msg' => _lang('app.Your M3RADYAPP verification code is: '). $verification_code,
            'fl' => 0
        ];

        $client = new \GuzzleHttp\Client(['base_uri' => $this->url]);
        $response = $client->request(
            'POST',
            'vendorsms/pushsms.aspx',
            [
                'form_params' => $data,
                'Content-Type' => 'application/json'

            ]
        );

        $responseJSON = json_decode($response->getBody(), true);
        return $responseJSON;

    }

}
