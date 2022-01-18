<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
    * The attributes that aren't mass assignable.
    *
    * @var array
    */
    protected $guarded = [];

    public function department(){

        return $this->belongsTo(Department::class, 'department_id');

    }

    public function employee_salary(){

        return $this->hasOne(EmployeeSalary::class);
    }
}
