<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessOutput extends Model
{
    use HasFactory;

    protected $table = 'process_output';

    protected $fillable = [
        'process_input_id',
        'process_output_tgl',
    ];

    protected $casts = [
        'process_output_tgl' => 'date',
    ];

    public function processInput()
    {
        return $this->belongsTo(
            ProcessInput::class,
            'process_input_id'
        );
    }

    public function processOutputData()
    {
        return $this->hasMany(
            ProcessOutputData::class,
            'process_output_id'
        );
    }
}