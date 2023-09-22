<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'name_prefix',
        'first_name',
        'middle_initial',
        'last_name',
        'gender',
        'email',
        'dob',
        'time_of_birth',
        'age_in_yrs',
        'date_of_joining',
        'age_in_company',
        'phone_no',
        'place_name',
        'county',
        'city',
        'zip',
        'region',
        'user_name',
    ];

    protected $guarded = ['id'];
}
