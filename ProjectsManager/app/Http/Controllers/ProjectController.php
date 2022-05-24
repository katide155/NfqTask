<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Student;
use App\Models\Group;
use App\Http\Controllers\StudentController;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	/*	'SELECT pr.*, count(st.student_project_title) as project_students
FROM projects as pr
LEFT JOIN students as st on pr.project_title = st.student_project_title
group by pr.id'*/

		StudentController::loadDataFromApi();
		
		$projects = Project::select("projects.*", DB::raw('count(students.student_project_title) as project_students'))
			->leftJoin('students', 'students.student_project_title', '=', 'projects.project_title')
			->groupBy('projects.id')
			->orderBy('projects.project_title', 'asc')->paginate(20);
		

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
		
		$api_student_link =  config('app.api_students_link');
		
		$request->validate([
			"project_title" => "required|min:2|max:50|string",
			"number_of_groups" => "required|min:1|max:50|integer",
			"max_number_students_in_group" => "required|min:1|max:100|integer",
		]);	
		
		
		
		if($project->project_title != $request->project_title)
		{
			$students = Student::where('student_project_title', '=', $project->project_title)->get();
		
			if($students){
				foreach($students as $student){
					
					
					$data = [
						'student_name' => $student->student_name,
						'student_surname' => $student->student_surname,
						'student_project_title' => $request->project_title,
						'student_group_title' => $student->student_group_title,
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
		
		
		if($project->number_of_groups > $request->number_of_groups){
			
			$limit = $project->number_of_groups - $request->number_of_groups;
			$offset = $request->number_of_groups;
			
			$groups = Group::where('group_project_id', '=', $project->id)->offset($offset)->limit($limit)->get();
			
		
			foreach ($groups as $group){
				
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
			}
		}
		if($project->number_of_groups < $request->number_of_groups){
			

			$project_title = $request->project_title;

			for($i=$project->number_of_groups; $i<$request->number_of_groups; $i++) {
				$group = new Group;
				$number = $i + 1;
				$group_title = $project_title.' '.$number. 'group';
				$group->group_title = $group_title;
				$group->max_number_students_in_group = $request->max_number_students_in_group;
				$group->group_project_id = $project->id;

				$group->save();
			}
		}
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
		$api_student_link =  config('app.api_students_link');
		
		$projectGroups = $project->projectGroups; 
		foreach ($projectGroups as $projectGroup){
				
				$groupStudents = Student::where('student_group_title', '=', $projectGroup->group_title)->get();
		
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
			
			$projectGroup->delete();
		}
		
        $project->delete();
		return redirect()->route('project.index')->with('success_message', 'Project successfully deleted.');
    }
	
	
	public function status(Request $request, Project $project)
    {
		StudentController::loadDataFromApi();
		
		//$projectGroups = $project->projectGroups; 
		//$projectStudents = $project->projectStudents;
		$projectGroups = Group::select("groups.*", DB::raw('count(students.student_group_title) as number_of_students_in_group'))
			->leftJoin('students', 'students.student_group_title', '=', 'groups.group_title')
			->where('groups.group_project_id', '=', $project->id)
			->groupBy('groups.id')
			->orderBy('groups.group_title', 'asc')->get();		
			
	
		$projectGroups = $projectGroups->toArray();
		$groups = [];
		foreach($projectGroups as $key => $projectGroup){
			
			$groupTitle = $projectGroup['group_title'];
			$groups[$groupTitle] = $projectGroup;
			$groupStudents=Student::where('student_group_title', '=', $groupTitle)->get();
			$groupStudents = $groupStudents->toArray();
			$groups[$groupTitle]['group_students'] = [];
			foreach($groupStudents as $key => $groupStudent){
					if($groupTitle == $groupStudent['student_group_title']){
						$groupStudentApiId = $groupStudent['api_student_id'];
						$groups[$groupTitle]['group_students'][$groupStudentApiId] = $groupStudent;
					}
			}
			
		}
											// echo '<pre>';
			// print_r($groupStudent);
			// echo '</pre>';		
		 // die;
		 $projectGroups = $groups;
		//dd($projectGroups);
		$projectStudents = Student::where('student_project_title', '=', $project->project_title)->orderBy('student_surname')->paginate(20);
		
		$students = Student::where('student_project_title', '=', null)->where('student_group_title', '=', null)->orderBy('student_surname')->get();

		return view('projects.status', ['project'=>$project, 'projectGroups'=>$projectGroups,'projectStudents'=>$projectStudents, 'students'=>$students]);
    }
	
		public function addgroup(Request $request)
    {
		$projectId = $request->project_id;
		
		if( isset($projectId) && !empty($projectId) ){
			

			$groupProject = Project::find($projectId);
			$number_of_groups = $groupProject->number_of_groups;
			$new_number_of_groups = $number_of_groups + 1;

			Project::where('id', $projectId)->update(['number_of_groups' => $new_number_of_groups]);
			
			$group = new Group;
			$number = $new_number_of_groups;
			$group_title = $groupProject->project_title.' '.$number. 'group';
			$group->group_title = $group_title;
			$group->max_number_students_in_group = $groupProject->max_number_students_in_group;
			$group->group_project_id = $projectId;	
			
			$group->save();
			
			return response()->json(array(
				'success_message' => 'Group added.',				
			));
		
		}

		return response()->json(array(
			'errors' => 'error message'
		));
	}
}
