<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Applicant extends Model
{
    use HasFactory;
    
     protected $table = 'applicants';

    protected $fillable = [
        'year',
        'season',
        'first_name',
        'last_name',
        'email',
        'phone',
        'dob',
        'gender',
        'category',
        'address',
        'city',
        'state',
        'pincode',
        'institute',
        'department',
        'area_of_interest',
        'educational_qualification',
        'studying_at_present',
        'presently_employed',
        'work_experience',
        'languages',
        'achievements',
        'sop',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}

 
                


           

           
           