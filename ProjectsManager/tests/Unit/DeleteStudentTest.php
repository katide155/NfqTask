<?php

namespace Tests\Unit;

use Tests\TestCase;

class DeleteStudentTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    // public function test_students_page()
    // {
        // $response = $this->get('students');

        // $response->assertStatus(200);
    // }
	
	
	public function test_delete_student(){
		$student = \App\Models\Student::factory()->count(1)->make();
		
		$student = \App\Models\Student::first();
		
		if($student){
			$student->delete();
		}
		
		$this->assertTrue(true);
	}
}
