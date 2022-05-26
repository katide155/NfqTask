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
		
		
				$sql = 'select * from (select groups.*, count(students.student_group_title) as number_of_students';
				$sql .=' from groups left join students on students.student_group_title = groups.group_title group by groups.id) as gr';
				$sql .=' where max_number_students_in_group > number_of_students order by group_title';
		
				$groups = DB::select($sql);
		
		// $sub_query = DB::table("groups")
			// ->leftJoin('students', 'students.student_group_title', '=', 'groups.group_title')->count('students.student_group_title')
			// ->groupBy('groups.id');

		// $groups = DB::table($sub_query, 'gr')
		// ->where('max_number_students_in_group', '>', 'number_of_students')
		// ->orderBy('groups.group_title')->get();
		
		
		
		// $groups = DB::table(DB::raw('(select groups.*, count(students.student_group_title) as number_of_students from groups left join students on students.student_group_title = groups.group_title group by groups.id))'). 'AS gr')

    // ->where('gr.max_number_students_in_group', '>', 'gr.number_of_students')
    // ->orderBy('gr.group_title')->get();

		// $groups = Group::select(DB::raw('COUNT(*) as total, modality'))
                // ->from(DB::raw('(SELECT modality AS modal, modality FROM series GROUP BY study_fk, modality) AS T'))
                // ->groupBy('modality')
				// ->get();

				$sql = 'select * from (select projects.*, count(students.student_project_title) as number_of_students, number_of_groups * max_number_students_in_group as max_number_students_in_project';
				$sql .=	' from projects left join students on students.student_project_title = projects.project_title group by projects.id) as pr';
				$sql .=	' where max_number_students_in_project > number_of_students order by project_title';

				$projects = DB::select($sql);		

	   $students = Student::select("students.*","projects.id as student_project_id")
			->leftJoin('projects', function($join){
		   $join->on('projects.project_title', '=', 'students.student_project_title');
	   })->orderBy('students.student_name')->paginate(20);
		
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
			
			$sql = "select * from (select groups.*, count(students.student_group_title) as number_of_students";
			$sql .= " from groups left join students on students.student_group_title = groups.group_title group by groups.id) as gr";
			$sql .= " where max_number_students_in_group > number_of_students and group_project_id = $projectId order by group_title";
		
			$projectGroups = DB::select($sql);

			return response()->json(array(
				'projectGroups' => $projectGroups				
			));
		
		}

		return response()->json(array(
			'error' => 'error'
		));
		
        
    }
	
	
	public function refresh(Request $request)
    {
		
		$studentId = $request->student_id;
		
		if( isset($studentId) && !empty($studentId) ){
			
			$sql = 'select * from (select groups.*, count(students.student_group_title) as number_of_students';
			$sql .=' from groups left join students on students.student_group_title = groups.group_title group by groups.id) as gr';
			$sql .=' where max_number_students_in_group > number_of_students order by group_title';

			$groups = DB::select($sql);

			$sql = 'select * from (select projects.*, count(students.student_project_title) as number_of_students, number_of_groups * max_number_students_in_group as max_number_students_in_project';
			$sql .=	' from projects left join students on students.student_project_title = projects.project_title group by projects.id) as pr';
			$sql .=	' where max_number_students_in_project > number_of_students order by project_title';

			$projects = DB::select($sql);
		
			return response()->json(array(
				'groups' => $groups,
				'projects' => $projects				
			));
		
		}

		return response()->json(array(
			'error' => 'error'
		));
	}
	
}
