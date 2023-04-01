<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BoatService;
use Illuminate\Support\Facades\Validator;

class BoatController extends Controller
{
    protected $boatService;

    public function __construct(BoatService $boatService)
    {
        $this->boatService = $boatService;
    }

    public function index(Request $request)
    {
        $result = $this->boatService->getData($request);
        if ($result['error'] === true) {
            return $this->errorResponse($result['message']);
        }
        return $this->successResponse($result['data'], $result['message']);
    }

    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:boats,code',
            'name' => 'required',
            'pemilik' => 'required',
            'alamat' => 'required',
            'ukuran' => 'required',
            'kapten' => 'required|string',
            'jumlah' => 'required|integer',
            'foto' => 'required|mimes:png,jpg,jpeg',
            'no_izin' => 'required',
            'document' => 'required|mimes:pdf'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $result = $this->boatService->insertData($request);
        if ($result['error'] === true) {
            return $this->errorResponse($result['message']);
        }
        return $this->successResponse($result['data'], $result['message']);
    }

    public function verifikasi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'status' => 'required',
            'note' => 'required_if:status,2'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $result = $this->boatService->verifikasiData($request);
        if ($result['error'] === true) {
            return $this->errorResponse($result['message']);
        }
        return $this->successResponse($result['data'], $result['message']);
    }
}
