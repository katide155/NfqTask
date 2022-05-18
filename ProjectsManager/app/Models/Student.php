<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
	
	public function studentGroup(){
		return $this->belongsTo(Group::class, 'student_group_id', 'id');
	}
}
