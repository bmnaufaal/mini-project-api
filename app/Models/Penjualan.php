<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table = 'penjualan';
    protected $primaryKey = 'id_nota';

    protected $fillable = [
        'id_nota', 'tgl', 'kode_pelanggan', 'subtotal',
    ];

    protected $casts = [
        'tgl' => 'date:d/m/Y'
    ];

    public $timestamps = false;
}
