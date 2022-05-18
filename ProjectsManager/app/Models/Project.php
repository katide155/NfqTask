<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
	
	public function projectGroups(){
		return $this->hasMany(Group::class, 'group_project_id', 'id');
	}
	
	public function projectStudents(){
		return $this->hasMany(Student::class, 'student_project_id', 'id');
	}
}
