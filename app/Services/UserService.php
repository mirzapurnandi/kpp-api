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
                $data = User::where('id', $id)->where('email_verified_at', '!=', null)->first();
                if ($data) {
                    if ($data->status == 0) {
                        $message = 'Akun Anda Sedang dalam di verifikasi oleh admin';
                    } else {
                        $message = 'Get Data User';
                    }
                } else {
                    $message = 'User Belum konfirmasi kode OTP';
                }
            } else {
                $data = User::where('level', 'user');
                if (isset($datas['status'])) {
                    $data = $data->where('status', $datas['status']);
                }
                $data = $data->get();
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

    public function konfirmasiData($otp)
    {
        $error = true;
        $message = '';
        $data = [];
        try {
            $user = auth()->user();
            $check = User::where('otp', $otp)->first();
            $error = false;
            if ($check) {
                $message = 'Successfully';
                $data = User::find($user->id);
                $data->otp = null;
                $data->email_verified_at = now();
                $data->save();
            }
        } catch (\Throwable $th) {
            $error = true;
            $message = $th->getMessage();
        }

        return [
            'error' => $error,
            'message' => $message,
            'data' => $data
        ];
    }

    public function verifikasiData($datas)
    {
        $error = true;
        $message = '';
        $data = [];
        try {
            $error = false;
            $check = User::where(['status' => 0, 'id' => $datas['user_id']])->first();
            if ($check) {
                $data = User::find($check->id);
                $data->status = true;
                $data->save();
                $message = 'Successfully update Status';
            } else {
                $message = 'User Not Found...';
            }
        } catch (\Throwable $th) {
            $error = true;
            $message = $th->getMessage();
        }

        return [
            'error' => $error,
            'message' => $message,
            'data' => $data
        ];
    }

    public function ExampleData($datas)
    {
        $error = true;
        $message = '';
        $data = [];
        try {
            $error = false;
            $message = 'Successfully';
        } catch (\Throwable $th) {
            $error = true;
            $message = $th->getMessage();
        }

        return [
            'error' => $error,
            'message' => $message,
            'data' => $data
        ];
    }
}
