@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-12">
			<h1>Project "{{$project->project_title}}"</h1>
			<h4>Number of groups: {{$project->number_of_groups}} </h4>
			<h4>Student per group: {{$project->max_number_students_in_group}}</h4>
		</div>
	</div>
	<div class="row">
		
		<div class="col-12">

				<div id="alert" class="alert d-none">
				</div>

			
			@if(count($projectStudents) == 0)
				
			<p>There are no student in this project</p>
			
			<p><a class="btn btn-success"  href="{{route('student.create')}}">Add new student</a></p>
			
			@else
				<h2>Project students</h2>
			<table class="table table-success table-striped">

				<thead>
					<tr>
						<th>Row No.</th>
						<th>Student</th>
						<th>Student group</th>
						<th style="width: 180px;">
							<a  type="button" href="{{route('student.create')}}" class="btn btn-success">Add new student</a>
						</th>
					</tr>
				</thead>
				
				<tbody>
					<?php $i=1; ?>
					@foreach ($projectStudents as $student)
					<tr>
						<td>{{ $i++; }}</td>
						<td><div>{{$student->student_name}} {{$student->student_surname}}</div></td>
						<td>
							{{$student->student_group_title}}
						</td>
						<td>
						<div class="btn-container">
							<a  type="button" href="{{route('student.edit',[$student])}}" class="btn btn-success dbfl edit-item act-item">..<span class="tooltipas">Edit</span></a>
							<form method="post" action="{{route('student.delete',[$student])}}" class="dbfl">
								@csrf
								<button type="submit" name="delete_student" class="btn btn-dangeris dbfl delete-item act-item">x<span class="tooltipas">Delete</span></button>
							</form>
							<a  type="button" href="{{route('student.show',[$student])}}" class="btn btn-primary dbfl show-item act-item"><span class="tooltipas">Show</span></a>
						</div>
						</td>
					</tr>
					@endforeach
				</tbody>
				
			</table>
	
			@endif
			<h2>Project groups</h2>			
			<p><a class="btn btn-success"  href="{{route('group.create')}}">Add new group</a></p>			
			@if(count($projectGroups) == 0)
				
			<p>There are no groups in this project</p>
			

			
			@else


				<div class="row">
					@foreach ($projectGroups as $group)
						<div class="col-sm-4 pb-5">
							<div class="card" style="width: 18rem;">
								<div class="card-header">
									{{$group['group_title']}}
								</div>
								<ul class="list-group list-group-flush">
									@if($group['group_students'])
									@foreach ($group['group_students'] as $student)
									<li class="list-group-item">
										<div class="row g-1 align-items-center">
											<div class="col-11">
												<input class="form-control form-control-sm" type="text" value="{{$student['student_name']}} {{$student['student_surname']}}" aria-label="readonly input example" readonly>
											</div>
											<div class="col-1">
												<button type="submit" name="delete_student" class="btn btn-dangeris dbfl delete-item act-item remove-student" student="{{$student['student_name']}} {{$student['student_surname']}}" student-id="{{$student['api_student_id']}}">x<span class="tooltipas">Remove</span></button>
											</div>
										</div>
									</li>
									@endforeach
									@endif
							
									@for($x = 1; $x <= ($group['max_number_students_in_group'] - $group['number_of_students_in_group']); $x++)
										<li class="list-group-item">
											<select class="form-select form-select-sm assign-student-to-group" group-title="{{$group['group_title']}}" project-title="{{$project->project_title}} "aria-label=".form-select-sm example">
												<option selected>Assign student to group</option>
												@foreach ($students as $student)
													<option value="{{$student->api_student_id}}">{{$student->student_name}} {{$student->student_surname}}</option>
												@endforeach
											</select>
										</li>
									@endfor
								</ul>
							</div>
						</div>
					@endforeach
				</div>
			@endif
			
			
			<script>
			
			let csrf = '123456789';

			
			$(document).ready(function(){

				
				$(document).on('change', '.assign-student-to-group', function(){	

					let student_id = $(this).val();
					let student_name_surname = $(this).find(":selected").text();
					const name_surname = student_name_surname.split(" ");
					let student_name = name_surname[0];
					let student_surname = name_surname[1];
					let student_group_title = $(this).attr('group-title');
					let student_project_title = $(this).attr('project-title');
					let action = "add";
					
			
					$.ajax({
						type: 'PUT',
						url: 'http://127.0.0.1:8080/api/students/'+student_id,
						data: {csrf:csrf, student_id:student_id, student_name:student_name, student_surname:student_surname, student_group_title:student_group_title, student_project_title:student_project_title, action:action},
						success: function(data){
							
							$('#alert').removeClass("alert-success");
							$('#alert').removeClass("alert-danger");
							//console.log(data.success_message);
							if($.isEmptyObject(data.error_message)){
									$('#alert').removeClass("d-none");
									$('#alert').addClass("alert-success"); 
									$('#alert').html(data.success_message);
									location.reload();						
							}else{
									$('#alert').removeClass("d-none");
									$('#alert').addClass("alert-danger");
									let error_massages;
									$.each(data.errors, function(key, error){
										error_massages += '<span>'+error+'</span></br>';
									});
									$('#alert').html(error_massages);
							}
							

						}
						
					});
					
				});
				
				$(document).on('click', '.remove-student', function(){	


					let student_id = $(this).attr('student-id');
					let student_name_surname = $(this).attr('student');
					const name_surname = student_name_surname.split(" ");
					let student_name = name_surname[0];
					let student_surname = name_surname[1];
					let student_group_title = null;
					let student_project_title = null;
					let action = "remove";
					
			
					$.ajax({
						type: 'PUT',
						url: 'http://127.0.0.1:8080/api/students/'+student_id,
						data: {csrf:csrf, student_id:student_id, student_name:student_name, student_surname:student_surname, student_group_title:student_group_title, student_project_title:student_project_title, action:action},
						success: function(data){
							
							$('#alert').removeClass("alert-success");
							$('#alert').removeClass("alert-danger");
							//console.log(data.success_message);
							if($.isEmptyObject(data.error_message)){
									$('#alert').removeClass("d-none");
									$('#alert').addClass("alert-success"); 
									$('#alert').html(data.success_message);
									location.reload();							
							}else{
									$('#alert').removeClass("d-none");
									$('#alert').addClass("alert-danger");
									let error_massages;
									$.each(data.errors, function(key, error){
										error_massages += '<span>'+error+'</span></br>';
									});
									$('#alert').html(error_massages);
							}
							

						}
						
					});
					
				});
				
			});
			
			</script>
		</div>
	</div>	
</div>
	
@endsection