<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item_Penjualan extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table = 'item_penjualan';

    protected $fillable = [
        'nota', 'kode_barang', 'qty'
    ];

    public $timestamps = false;
}
