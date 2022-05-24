<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Group;
use App\Models\Project;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$this->loadDataFromApi();
		
		$groups = Group::select("groups.*", DB::raw('count(students.student_group_title) as number_of_students_in_group'))
			->leftJoin('students', 'students.student_group_title', '=', 'groups.group_title')
			->where('groups.max_number_students_in_group', '>', 'number_of_students_in_group')
			->groupBy('groups.id')
			->orderBy('groups.group_title', 'asc')->get();
		
		//$groups = Group::all();
		$projects = Project::all();
		$students = Student::paginate(20);
        return view('students.index',['students'=> $students, 'groups'=> $groups, 'projects'=> $projects]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
	
	public function loadDataFromApi(){
		
		$api_student_link =  config('app.api_students_link');
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $api_student_link."?csrf=123456789&allList=all",
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_ENCODING => "",
			CURLOPT_TIMEOUT => 30000,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
			),
		));
		
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		
		$deleteStudent = Student::all();
		if(count($deleteStudent)>0){
			foreach($deleteStudent as $row){
				$row->delete();
			}
		}
		
		$students = json_decode($response);
		
		foreach($students as $student){
			$newStudent = new Student;
			$newStudent->student_name = $student->student_name;
			$newStudent->student_surname = $student->student_surname;
			$newStudent->student_group_title = $student->student_group_title;
			$newStudent->student_project_title = $student->student_project_title;
			$newStudent->api_student_id = $student->id;
			$newStudent->save();
		};
	}
	
	
	public function change(Request $request)
    {
		
		$projectId = $request->project_id;
		
		if( isset($projectId) && !empty($projectId) ){
			
		$groups = Group::select("groups.*", DB::raw('count(students.student_group_title) as number_of_students_in_group'))
			->leftJoin('students', 'students.student_group_title', '=', 'groups.group_title')
			->where('groups.max_number_students_in_group', '>', 'number_of_students_in_group')
			->where('groups.group_project_id', '=', $projectId)
			->groupBy('groups.id')
			->orderBy('groups.group_title', 'asc')->get();
			
			
			return response()->json(array(
				'projectGroups' => $projectGroups				
			));
		
		}

		return response()->json(array(
			'error' => 'error message'
		));
		
        
    }
	
}
