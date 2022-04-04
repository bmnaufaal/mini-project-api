<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $table = 'barang';
    protected $primaryKey = 'kode';

    protected $fillable = [
        'kode', 'nama', 'kategori', 'harga',
    ];

    public $timestamps = false;
}
