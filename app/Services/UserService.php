<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;

class UserService
{
    protected $name;
    protected $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->name = 'User';

        $this->tokenService = $tokenService;
    }

    public function getData($datas, $id = null)
    {
        $error = true;
        $message = '';
        $data = [];
        try {
            if ($id != "") {
                $data = User::where('id', $id)->first();
                $message = 'Get Data User';
            } else {
                $data = User::all();
                $message = 'Get All Data User';
            }
            $error = false;
        } catch (\Throwable $th) {
            $message = $th->getMessage();
        }

        return [
            'error' => $error,
            'message' => $message,
            'data' => $data
        ];
    }

    public function insertData($request)
    {
        $error = true;
        $data = [];
        $message = '';
        try {
            $uuid = Str::uuid()->getHex()->toString();
            $otp = random_int(100000, 999999);

            $data = new User();
            $data->name = $request->name;
            $data->email = $request->email;
            $data->password = bcrypt($request->password);
            $data->uuid = $uuid;
            $data->otp = $otp;
            $data->save();

            $error = false;
            $message = 'Successfully insert data User';
            $getOtp = $this->tokenService->getOTP([
                'nama' => $data->name,
                'email' => $data->email,
                'otp' => $otp
            ]);
            if ($getOtp['error'] == false) {
                $message .= ' and success send OTP email.';
            } else {
                $message .= ' and invalid send OTP email.';
            }
        } catch (\Throwable $th) {
            $message = $th->getMessage();
            $error = true;
        }

        return [
            'error' => $error,
            'message' => $message,
            'data' => $data
        ];
    }

    public function updateData($request)
    {
        //
    }

    public function deleteData($id)
    {
        //
    }
}
