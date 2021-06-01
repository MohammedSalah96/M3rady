<?php

namespace App\Helpers;


class HiWhatsapp
{

    private $url    = "https://hiwhats.com/";
    private $mobile  = "966592806688";
    private $password = '001A002003004005';
    private $instanceid = 19470;

    public function send($to, $verificationCode)
    {
        $data = [
            'mobile' => $this->mobile,
            'password' => $this->password,
            'numbers' => $to,
            'instanceid' => $this->instanceid,
            'message' => _lang('app.Your M3RADYAPP verification code is: ') . $verificationCode,
            'json' => 1,
            'type' => 1
        ];

        $client = new \GuzzleHttp\Client(['base_uri' => $this->url]);
        $response = $client->request(
            'GET',
            'API/send',
            [
                'query' => $data
            ]
        );

        $responseJSON = json_decode($response->getBody(), true);
        /*$msgId = explode(':', $responseJSON['msg'][0])[1];
       
        $statusData = [
            'mobile' => $this->mobile,
            'password' => $this->password,
            'msgid' => $msgId,
            'json' => 1,
        ];
        $statusResponse = $client->request(
            'GET',
            'API/message',
            [
                'query' => $statusData
            ]
        );
        $statusResponseJSON = json_decode($statusResponse->getBody(), true);*/
       
        return $responseJSON;
    }
}
