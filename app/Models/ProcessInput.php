<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessInput extends Model
{
    use HasFactory;

    protected $table = 'process_input';

    protected $fillable = [
        'process_input_tgl',
        'operasional',
    ];

    protected $casts = [
        'process_input_tgl' => 'date',
    ];

    public function processInputData()
    {
        return $this->hasMany(
            ProcessInputData::class,
            'process_input_id'
        );
    }

    public function processOutput()
    {
        return $this->hasMany(
            ProcessOutput::class,
            'process_input_id'
        );
    }
}