<?php
// src/ShinyBundle/Services/reCaptcha.php

namespace ShinyBundle\Services;


class reCaptcha
{
    private $secret_recaptcha;

    public function __construct($secret_recaptcha)
    {
        $this->secret_recaptcha = $secret_recaptcha;
    }

    public function captchaverify($recaptcha){
//        $url = "https://www.google.com/recaptcha/api/siteverify";
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_HEADER, 0);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
//            "secret"=>$this->secret_recaptcha,"response"=>$recaptcha));
//        $response = curl_exec($ch);
//        curl_close($ch);
//        $data = json_decode($response);

        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$this->secret_recaptcha."&response=".$recaptcha);
        $responseKeys = json_decode($response,true);

        return $responseKeys['success'];
    }
}