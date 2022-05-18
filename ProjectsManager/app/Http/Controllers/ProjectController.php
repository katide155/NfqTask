<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Student;
use App\Models\Group;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$projects = Project::orderBy('project_title')->paginate(20);
		$groups = Group::all();
		return view('projects.index', ['projects'=>$projects],['project'=>$groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$request->validate([
			"project_title" => "required|min:2|max:50|string",
			"number_of_groups" => "required|min:1|max:50|integer",
			"max_number_students_in_group" => "required|min:1|max:100|integer",
		]);	
		
        $project = new Project;
		$project->project_title = $request->project_title;
		$project->number_of_groups = $request->number_of_groups;
		$project->max_number_students_in_group = $request->max_number_students_in_group;
	
		$project->save();
		
		$project_title = $request->project_title;
		$total = $request->number_of_groups;
		
			for($i=0; $i<$total; $i++) {
				$group = new Group;
				$number = $i + 1;
				$group_title = $project_title.' '.$number. 'group';
				$group->group_title = $group_title;
				$group->max_number_students_in_group = $request->max_number_students_in_group;
				$group->group_project_id = $project->id;

				$group->save();
			}
		
		return redirect()->route('project.index');
		
		
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('projects.show', ['project'=>$project]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('projects.edit', ['project'=>$project]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProjectRequest  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
		$request->validate([
			"project_title" => "required|min:2|max:50|string",
			"number_of_groups" => "required|min:1|max:50|integer",
			"max_number_students_in_group" => "required|min:1|max:100|integer",
		]);	
		
		$project->project_title = $request->project_title;
		$project->number_of_groups = $request->number_of_groups;
		$project->max_number_students_in_group = $request->max_number_students_in_group;
	
		$project->save();
		return redirect()->route('project.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
		$projectGroups = $project->projectGroups; 
		foreach ($projectGroups as $projectGroup){
			$projectGroup->delete();
		}
		
        $project->delete();
		return redirect()->route('project.index')->with('success_message', 'Project successfully deleted.');
    }
}
