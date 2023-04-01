<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $result = $this->userService->getData([], $this->guard()->id());
        if ($result['error'] === true) {
            return $this->errorResponse($result['message']);
        }
        return $this->successResponse($result['data'], $result['message']);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        if (!$token = Auth::guard('api')->attempt($validator->validated())) {
            return $this->errorResponse('Unuathorized', Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $result = $this->userService->insertData($request);
        if ($result['error'] === true) {
            return $this->errorResponse($result['message']);
        }

        return $this->successResponse($result['data'], $result['message']);
    }

    public function konfirmasi(Request $request)
    {
        $result = $this->userService->konfirmasiData($request->otp);
        if ($result['error'] == true) {
            return $this->errorResponse($result['message']);
        }
        return $this->successResponse($result['data'], $result['message']);
    }

    public function logout()
    {
        $this->guard()->logout();
        return $this->successResponse(null, 'User Logout successfully');
    }
}
