<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Student;
use App\Models\Project;
use App\Http\Controllers\StudentController;
use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\UpdateGroupRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		StudentController::loadDataFromApi();
		
		$groups = Group::select("groups.*", DB::raw('count(students.student_group_title) as group_students'))
			->leftJoin('students', 'students.student_group_title', '=', 'groups.group_title')
			->groupBy('groups.id')
			->orderBy('groups.group_title', 'asc')->paginate(20);
		
		
		return view('groups.index', ['groups'=>$groups]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		$projects = Project::all();
        return view('groups.create', ['projects'=>$projects]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGroupRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$request->validate([
			"group_title" => "required|min:2|max:50|string",
			"group_project_id" => "required|min:1|max:100|integer",
		]);	


		
		$groupProjectId = $request->group_project_id;
		$groupProject = Project::find($groupProjectId);
		$number_of_groups = $groupProject->number_of_groups;
		$new_number_of_groups = $number_of_groups + 1;

		Project::where('id', $groupProjectId)->update(['number_of_groups' => $new_number_of_groups]);
		
		$group = new Group;
		$group->group_title = $request->group_title;
		$group->max_number_students_in_group = $groupProject->max_number_students_in_group;
		$group->group_project_id = $request->group_project_id;	
		
		$group->save();
		return redirect()->route('group.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
		StudentController::loadDataFromApi();
		$groupStudents = Student::where('student_group_title', '=', $group->group_title)->get();
        return view('groups.show', ['group'=>$group, 'groupStudents'=>$groupStudents ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
		
        return view('groups.edit', ['group'=>$group]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateGroupRequest  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
		$request->validate([
			"group_title" => "required|min:2|max:50|string"
		]);	
		
		Group::where('id', $group->id)->update(['group_title' => $request->group_title]);
		
		$api_student_link =  config('app.api_students_link');
		
		if($group->group_title != $request->group_title)
		{
			$students = Student::where('student_group_title', '=', $group->group_title)->get();
		
			if($students){
				foreach($students as $student){
					
					
					$data = [
						'student_name' => $student->student_name,
						'student_surname' => $student->student_surname,
						'student_project_title' => $student->student_project_title,
						'student_group_title' => $request->group_title,
						'csrf' => '123456789'
					];			
				
					$id = $student->api_student_id;
					
					$curl = curl_init();
					curl_setopt_array($curl, array(
						CURLOPT_URL => $api_student_link.'/'.$id,
						CURLOPT_CUSTOMREQUEST => "PUT",
						CURLOPT_ENCODING => "",
						CURLOPT_TIMEOUT => 30000,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_POSTFIELDS => json_encode($data),
						CURLOPT_HTTPHEADER => array(
							'Content-Type: application/json',
						),
					));
				
					$response = curl_exec($curl);
					$err = curl_error($curl);
					curl_close($curl);
					
				}
			
				StudentController::loadDataFromApi();
			}
		}

		return redirect()->route('group.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
		
		$groupProject = Project::find($group->group_project_id);
		$number_of_groups = $groupProject->number_of_groups;
		$new_number_of_groups = $number_of_groups - 1;

		Project::where('id', $groupProject->id)->update(['number_of_groups' => $new_number_of_groups]);		
		
		$api_student_link =  config('app.api_students_link');
		
		$groupStudents = Student::where('student_group_title', '=', $group->group_title)->get();

		if($groupStudents){
			foreach($groupStudents as $student){
				
				
				$data = [
					'student_name' => $student->student_name,
					'student_surname' => $student->student_surname,
					'student_group_title' => null,
					'student_project_title' => null,
					'csrf' => '123456789'
				];			
			
				$id = $student->api_student_id;
				
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_URL => $api_student_link.'/'.$id,
					CURLOPT_CUSTOMREQUEST => "PUT",
					CURLOPT_ENCODING => "",
					CURLOPT_TIMEOUT => 30000,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_POSTFIELDS => json_encode($data),
					CURLOPT_HTTPHEADER => array(
						'Content-Type: application/json',
					),
				));
			
				$response = curl_exec($curl);
				$err = curl_error($curl);
				curl_close($curl);
				
			}
		
			StudentController::loadDataFromApi();
		}
		
        $group->delete();
		return redirect()->route('group.index')->with('success_message', 'Group succsessfully deleted.');
    }
}
