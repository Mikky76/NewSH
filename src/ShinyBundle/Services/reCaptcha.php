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
        $response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$this->secret_recaptcha."&response=".$recaptcha);
        $responseKeys = json_decode($response,true);

        return $responseKeys['success'];
    }
}