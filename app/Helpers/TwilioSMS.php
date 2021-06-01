<?php

namespace App\Helpers;

use Twilio\Rest\Client;

Class TwilioSMS {
  
    private $sid    = "AC98878eaadbda7496a28c6c70dc8e1b63";
    private $token  = "916c85c22a4da37c79ef77b994bf5b02";
    private $from = '+16575005053';

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
