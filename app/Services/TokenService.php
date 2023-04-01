<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TokenService
{
    protected $name;

    public function __construct()
    {
        $this->name = 'OTP';
    }

    public function getOTP($data)
    {
        $error = true;
        $message = '';
        try {
            $response = Http::acceptJson()->post('https://script.google.com/macros/s/AKfycbxFNsyMXW8chGL8YhdQE1Q1yBbx5XEsq-BJeNF1a6sKoowaL_9DtcUvE_Pp0r5ootgMhQ/exec', [
                'email' => $data['email'],
                'subject' => "Pengiriman OTP",
                "message" => "Terimakasih " . $data['nama'] . ", berikut kode OTP " . $data['otp'],
                'token' => "1dy09eODblmBUCTnIwiY-hbXdzCpZC3jyR4l0ZJgqQqO9L7J3zsZOobdJ"
            ]);

            $result = $response->body();

            if ($result['message'] == "Email sent") {
                $error = false;
            }
        } catch (\Throwable $th) {
            $error = true;
            $message = $th->getMessage();
        }

        return [
            'error' => $error,
            'message' => $message,
        ];
    }
}
