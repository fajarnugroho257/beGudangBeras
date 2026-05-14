<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessInputData extends Model
{
    use HasFactory;

    protected $table = 'process_input_data';

    protected $fillable = [
        'process_input_id',
        'barang_id',
        'supplier_id',
        'tonase',
    ];

    public function processInput()
    {
        return $this->belongsTo(
            ProcessInput::class,
            'process_input_id'
        );
    }

    public function barang()
    {
        return $this->belongsTo(
            Barang::class,
            'barang_id'
        );
    }

    public function supplier()
    {
        return $this->belongsTo(
            Suplier::class,
            'supplier_id'
        );
    }
}