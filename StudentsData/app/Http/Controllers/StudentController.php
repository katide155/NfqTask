<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
		$csrf = $request->csrf;
		$allList = $request->allList;
		
		if( isset($csrf) && !empty($csrf) && $csrf == '123456789' ){
			
			if( isset($allList) && !empty($allList) &&  $allList == 'all'){
				$students = Student::all();
				return response()->json($students);
			}else{
				$students = Student::paginate(20);
				return response()->json($students);
			}

		}
		
		return response()->json(array(
			'error' => 'Autentification failed!'
		));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		
		$csrf = $request->csrf;
		
		if( isset($csrf) && !empty($csrf) && $csrf == '123456789' ){		
		
			$input = [
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
			

			
			$validator = Validator::make($input, $rules);
			
			if($validator->fails()){
				
				$errors = $validator->messages()->get('*');
				return response()->json(array(
					'error_message' => 'Error',
					'errors' => $errors
				));			
				
				
			}else{
				$student = new Student;
				
				$student->student_name = $request->student_name;
				$student->student_surname = $request->student_surname;
				$student->student_group_title = $request->student_group_title;
				$student->student_project_title = $request->student_project_title;
				
				$student->save();
				
				return response()->json(array(
					'success_message' => 'Student '.$student->student_name.' '.$student->student_surname.' successfully added to "'.$student->student_group_title.'"',
				));
			}
		
		}
		
		return response()->json(array(
			'error' => 'Autentification failed!'
		));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = Student::find($id);
		
		return response()->json($student);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$csrf = $request->csrf;
		
		if( isset($csrf) && !empty($csrf) && $csrf == '123456789' ){
		
			$input = [
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
			

			
			$validator = Validator::make($input, $rules);
			
			if($validator->fails()){
				
				$errors = $validator->messages()->get('*');
				return response()->json(array(
					'error_message' => 'Error',
					'errors' => $errors
				));			
				
				
			}else{
				$student = Student::find($id);
				$student->student_name = $request->student_name;
				$student->student_surname = $request->student_surname;
				$student->student_group_title = $request->student_group_title;
				$student->student_project_title = $request->student_project_title;
				
				$student->save();
				
				return response()->json(array(
					'success_message' => 'Student '.$student->student_name.' '.$student->student_surname.' successfully added to "'.$student->student_group_title.'"',
				));
			}
		
		}
		
		return response()->json(array(
			'error' => 'Autentification failed!'
		));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = Student::find($id);
		$student->delete();
		
		return response()->json(array(
			'successMessage' => 'Student deleted'
		));
    }
}
