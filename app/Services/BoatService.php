<?php

namespace App\Services;

use App\Models\Boat;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class BoatService
{
    protected $name;

    public function __construct()
    {
        $this->name = 'Boat';
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
}
