<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
	
	public function groupProject(){
		return $this->belongsTo(Project::class, 'group_project_id', 'id');
	}
}
