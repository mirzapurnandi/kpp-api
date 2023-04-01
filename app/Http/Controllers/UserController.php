<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $data = [];
        if (isset($request->status)) {
            $data = ['status' => $request->status];
        }
        $result = $this->userService->getData($data);
        if ($result['error'] === true) {
            return $this->errorResponse($result['message']);
        }
        return $this->successResponse($result['data'], $result['message']);
    }

    public function verifikasi(Request $request)
    {
        $result = $this->userService->verifikasiData(['user_id' => $request->user_id]);
        if ($result['error'] === true) {
            return $this->errorResponse($result['message']);
        }
        return $this->successResponse($result['data'], $result['message']);
    }
}
