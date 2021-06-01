<?php

namespace App\Helpers;

use Twilio\Rest\Client;

Class TwilioSMS {
  
    private $sid    = "";
    private $token  = "";
    private $from = '';

    public function send($to, $verificationCode)
    {
        $twilio = new Client($this->sid, $this->token);
        
        $message = $twilio->messages
            ->create(
                $to, // to
                [
                    "body" => "Your M3rady verification code is: ". $verificationCode,
                    "from" => $this->from
                ]
            );
        
        return $message;
    }

}
