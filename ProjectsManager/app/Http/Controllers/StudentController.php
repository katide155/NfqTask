<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Group;
use App\Models\Project;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Illuminate\Http\Request;
use Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//$this->loadDataFromApi();
		$groups = Group::all();
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
		
			$this->loadDataFromApi();
			
			return redirect()->route('student.index');
			
			
       /*	$data = [
			'student_name' => $request->student_name,
			'student_surname' => $request->student_surname,
			'student_group_title' => $request->student_group_title,
			'student_project_title' => $request->student_project_title,
		];
		
		
		$rules = [
			'student_name' => 'required|string|max:100',
			'student_surname' => 'required|string|max:100',
			'student_group_title' => 'string|max:100|nullable',
			'student_project_title' => 'string|max:100|nullable',
		];

	
		$validator = Validator::make($data, $rules);
		
		if($validator->fails()){
			
			$errors = $validator->messages()->get('*');
			return response()->json(array(
				'error_message' => 'Error',
				'errors' => $errors
			));	
			
		}else{

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "http://127.0.0.1:8080/api/students",
				CURLOPT_CUSTOMREQUEST => "POST",
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
			$this->loadDataFromApi();
			
			return redirect()->route('student.index');
		}
		
		*/
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
       	$data = [
			'student_name' => $request->student_name,
			'student_surname' => $request->student_surname,
			'student_group_title' => $request->student_group_title,
			'student_project_title' => $request->student_project_title,
		];
		
		
		$rules = [
			'student_name' => 'required|string|max:100',
			'student_surname' => 'required|string|max:100',
			'student_group_title' => 'string|max:100|nullable',
			'student_project_title' => 'string|max:100|nullable',
		];

	
		$validator = Validator::make($data, $rules);
		
		if($validator->fails()){
			
			$errors = $validator->messages()->get('*');
			return response()->json(array(
				'error_message' => 'Error',
				'errors' => $errors
			));	
			
		}else{

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "http://127.0.0.1:8080/api/students/".$request->student_api_id,
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
			$this->loadDataFromApi();
			
			return redirect()->route('student.index');
		}
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
		
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => "http://127.0.0.1:8080/api/students?csrf=123456789&allList=all",
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
}
