<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessOutputData extends Model
{
    use HasFactory;

    protected $table = 'process_output_data';

    protected $fillable = [
        'process_output_id',
        'barang_id',
        'supplier_id',
        'tonase',
    ];

    public function processOutput()
    {
        return $this->belongsTo(
            ProcessOutput::class,
            'process_output_id'
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