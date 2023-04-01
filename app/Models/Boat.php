<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'name',
        'pemilik',
        'alamat',
        'ukuran',
        'kapten',
        'jumlah',
        'foto',
        'no_izin',
        'document',
        'status',
        'note'
    ];
}
