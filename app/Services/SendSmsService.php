<?php

namespace App\Services;

class SendSmsService
{
    public static function sendnowsms($to, $msg)
    {
        $mobile = "63" . substr($to, 1, 10);

        $destination = $mobile;
        $message = $msg;
        $message = html_entity_decode($message, ENT_QUOTES, 'utf-8');
        $message = urlencode($message);

        $username = urlencode("nlendio");
        $password = urlencode("noister123$");
        $sender_id = urlencode("JDEN SMS");
        $type = 1;

        $fp = "https://www.isms.com.my/isms_send.php";
        $fp .= "?un=$username&pwd=$password&dstno=$destination&msg=$message&type=$type&sendid=$sender_id&agreedterm=YES";
        $response = self::ismscURL($fp);

        if ($response['status'] == 200) {
            return [
                'success' => true,
                'response' => $response['result']
            ];
        }

        return [
            'success' => false,
            'status' => $response['status'],
            'response' => $response['result']
        ];
    }

    public static function ismscURL($link)
    {
        $http = curl_init($link);

        curl_setopt($http, CURLOPT_RETURNTRANSFER, TRUE);
        $http_result = curl_exec($http);
        $http_status = curl_getinfo($http, CURLINFO_HTTP_CODE);
        curl_close($http);

        return [
            'status' => $http_status,
            'result' => $http_result
        ];
    }

    public function formatPHNumber($number)
    {
        $number = preg_replace('/[^0-9]/', '', $number);

        if (substr($number, 0, 2) === '63') {
            return $number;
        }

        if (substr($number, 0, 1) === '0') {
            return '63' . substr($number, 1);
        }

        if (strlen($number) === 10 && substr($number, 0, 1) === '9') {
            return '63' . $number;
        }

        return $number;
    }
}
