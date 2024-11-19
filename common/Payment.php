<?php

class PAYMENT
{

    private $xapiKey;
    private $xbaseUrl;
    private $xUrl;

    private $papiKey;
    private $pbaseUrl;
    private $pUrl;

    public function __construct()
    {
        $this->xapiKey  = "XPPUBK-19995e83ba654840be35242359b66f8c-X";
        $this->xbaseUrl =  'https://myxpresspay.com:6004'; //Test: https://pgsandbox.xpresspayments.com:8090
        $this->xUrl     = "/api/Payments/Initialize";

        $this->papiKey  = "sk_test_17cfff997f0293b3ae8c0e5164e32b06c03c1f75";
        $this->pbaseUrl =  'https://api.paystack.co';
        $this->pUrl     = "/transaction/initialize";
    }

    // Payment Initialization .......................................................
    public function PayStack($data)
    {

        $apiUrl = $this->pbaseUrl . $this->pUrl;

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->papiKey,
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            // Handle the API response
            $responseData = json_decode($response, true);
            return $responseData;
        }

        curl_close($ch);
    }


    public function Xpresspay($data)
    {

        // // $apiUrl = 'https://api.xpress-pay.com/payment/v1/payments';
        // https://myxpresspay.com:6004   - live
        // https://pgsandbox.xpresspayments.com:8090
        $apiUrl = $this->xbaseUrl . $this->xUrl;

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->xapiKey,
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            // Handle the API response
            $responseData = json_decode($response, true);
            return $responseData;
        }

        curl_close($ch);
    }
    // ...............................................................................

    // Payment verifications .........................................................
    public function verifyPaystack($PaymentRef)
    {

        $apiUrl = "https://api.paystack.co/transaction/verify/{$PaymentRef}";

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->papiKey,
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            // Handle the API response
            $responseData = json_decode($response, true);
            return $responseData;
        }

        curl_close($ch);
    }

    public function verifyXpresspay($data)
    {


        $apiUrl = $apiUrl = $this->xbaseUrl . "/api/Payments/VerifyPayment"; //live: https://myxpresspay.com:6004/api/Payments/VerifyPayment

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->xapiKey,
        ];

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        } else {
            // Handle the API response
            $responseData = json_decode($response);
            return $responseData;
        }

        curl_close($ch);
    }
}
