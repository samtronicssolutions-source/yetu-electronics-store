<?php
require_once 'config.php';

class Mpesa {
    private $consumer_key;
    private $consumer_secret;
    private $shortcode;
    private $passkey;
    private $callback_url;
    private $env;
    
    public function __construct() {
        $this->consumer_key = MPESA_CONSUMER_KEY;
        $this->consumer_secret = MPESA_CONSUMER_SECRET;
        $this->shortcode = MPESA_SHORTCODE;
        $this->passkey = MPESA_PASSKEY;
        $this->callback_url = MPESA_CALLBACK_URL;
        $this->env = MPESA_ENV;
    }
    
    private function getAccessToken() {
        $url = $this->env == 'sandbox' 
            ? 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials'
            : 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
        
        $credentials = base64_encode($this->consumer_key . ':' . $this->consumer_secret);
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . $credentials]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $result = json_decode($response);
        
        curl_close($ch);
        
        return $result->access_token;
    }
    
    public function stkPush($phone, $amount, $order_number) {
        $access_token = $this->getAccessToken();
        
        $timestamp = date('YmdHis');
        $password = base64_encode($this->shortcode . $this->passkey . $timestamp);
        
        $curl_post_data = [
            'BusinessShortCode' => $this->shortcode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone,
            'PartyB' => $this->shortcode,
            'PhoneNumber' => $phone,
            'CallBackURL' => $this->callback_url,
            'AccountReference' => $order_number,
            'TransactionDesc' => 'Payment for order ' . $order_number
        ];
        
        $url = $this->env == 'sandbox' 
            ? 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
            : 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $access_token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($curl_post_data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($response);
    }
}
?>
