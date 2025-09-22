<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\documents as Authenticatable;

class Document extends Model
{
    use HasFactory;

     protected $table = 'documents';

    protected $fillable = [
        'applicant_id',
        'file_type',
        'file_path',
        'original_name',
    ];

    public function applicant()
    {
        return $this->belongsTo(Applicant::class);
    }
}