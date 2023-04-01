<?php

namespace App\Services;

use App\Models\Boat;

class BoatService
{
    protected $name;

    public function __construct()
    {
        $this->name = 'Boat';
    }

    public function getData($request)
    {
        $error = true;
        $message = '';
        $data = [];
        try {
            $user = auth()->user();
            if ($user->level == 'admin') {
                $data = Boat::all();
            } else {
                $data = Boat::where('user_id', $user->id);
                if ($request->status > 0) $data = $data->where('status', $request->status);
                $data = $data->get();
            }

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

    public function insertData($request)
    {
        $error = true;
        $message = '';
        $data = [];
        try {
            $name_foto = '';
            if ($request->hasfile('foto')) {
                $file = $request->file('foto');
                $name_foto = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/foto/'), $name_foto);
            }

            $name_document = '';
            if ($request->hasfile('document')) {
                $file = $request->file('document');
                $name_document = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/document/'), $name_document);
            }

            $data = new Boat();
            $data->user_id = auth()->user()->id;
            $data->code = $request->code;
            $data->name = $request->name;
            $data->pemilik = $request->pemilik;
            $data->alamat = $request->alamat;
            $data->ukuran = $request->ukuran;
            $data->kapten = $request->kapten;
            $data->jumlah = $request->jumlah;
            $data->foto = $name_foto;
            $data->no_izin = $request->no_izin;
            $data->document = $name_document;
            $data->save();

            $error = false;
            $message = 'Successfully insert Boat';
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

    public function verifikasiData($request)
    {
        $error = true;
        $message = '';
        $data = [];
        try {
            $error = false;

            $check = Boat::where('code', $request->code)->first();
            if ($check) {
                $data = Boat::find($check->id);
                $data->status = $request->status;
                $data->note = $request->note ? $request->note : null;
                $data->save();
                $message = 'Successfully update Status Boat';
            } else {
                $message = 'Boat Not Found...';
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
}
