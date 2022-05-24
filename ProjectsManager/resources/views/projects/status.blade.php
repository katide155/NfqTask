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
			
			<p><button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearValues()">Add new student</button></p>
			
			@else
				<h2>Project students</h2>
			<table class="table table-success table-striped">

				<thead>
					<tr>
						<th>Row No.</th>
						<th>Student</th>
						<th>Student group</th>
						<th style="width: 180px;">
							<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearValues()">Add new student</button>
						</th>
					</tr>
				</thead>
				
				<tbody>
					<?php $i=1; ?>
					@foreach ($projectStudents as $student)
					<tr>
						<td>{{ $i++; }}<input type="hidden" id="student_api_id_{{$student->id}}" student_api_id="{{$student->api_student_id}}" /></td>
						<td><span id="student_name_{{$student->id}}">{{$student->student_name}}</span> <span id="student_surname_{{$student->id}}">{{$student->student_surname}}</span></td>
						<td id="student_group_title_{{$student->id}}"  student-group="{{$student->student_group_title}}">
							{{$student->student_group_title}}
						</td>
						<td>
							<div class="btn-container">
								<button type="button" class="btn btn-success dbfl edit-item act-item" data-bs-id="1" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="setIdToEdit({{$student->id}})">
									..<span class="tooltipas">Edit</span>
								</button>
								<button type="submit" name="delete_child" class="btn btn-dangeris dbfl delete-student act-item" student_api_id="{{$student->api_student_id}}">x<span class="tooltipas">Delete</span></button>
							</div>
						</td>
					</tr>
					@endforeach
				</tbody>
				
			</table>
	
			@endif
			<h2>Project groups</h2>			
			<p><button class="btn btn-success" project-id="{{$project->id}}" id="add_new_group">Add new group</button></p>			
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
			
			<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Student data</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div class="row g-3 align-items-center">
								<div class="col-4">
									<input type="hidden" id="student_api_id" name="student_api_id" />
									<input type="hidden" id="student_project_title" name="student_project_title" value="{{$project->project_title}}"/>
									<label for="student_name" class="col-form-label">Student name</label>
								</div>
								<div class="col-8">
									<input type="text" id="student_name" name="student_name" class="form-control" aria-describedby="passwordHelpInline">
									<span class="invalid-feedback input_student_name" role="alert"></span>
								</div>
							</div>
						</div>
						<div class="modal-body">
							<div class="row g-3 align-items-center">
							  <div class="col-4">
								<label for="student_surname" class="col-form-label">Student surname</label>
							  </div>
							  <div class="col-8">
								<input type="text" id="student_surname" name="student_surname" class="form-control"  aria-describedby="passwordHelpInline">
								<span class="invalid-feedback input_student_surname" role="alert"></span>
							  </div>
							</div>
						</div>
						<div class="modal-body">
							<div class="row g-3 align-items-center">
							  <div class="col-4">
								<label for="student_group_title" class="col-form-label">Student group</label>
							  </div>
								<div class="col-8">
									<select id="student_group_title" class="form-select" aria-label=".form-select-sm example" name="student_group_title">
										@foreach ($projectGroups as $group)
										<option value="{{$group['group_title']}}">{{$group['group_title']}}</option>
										@endforeach
									</select>
									<span class="invalid-feedback input_student_group_title" role="alert"></span>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button class="btn btn-success" type="submit" id="save_student">Save</button>
							<button class="btn btn-success" type="submit" id="edit_student">Edit</button>
						</div>
					</div>
				</div>
			</div>
			<script>

			$.ajaxSetup({
				
				headers:{
					"X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
				}
				
			});
			
			let csrf = '123456789';
			
			function clearValues(){
				$('#edit_student').addClass("d-none");
				$('#save_student').removeClass("d-none");
				$('.is-invalid').removeClass('is-invalid');
				setElementValue('student_name', '');
				setElementValue('student_surname', '');
				setElementValue('student_group_title', '');
				document.getElementById('student_group_title').selected = false;
				$('.is-invalid').removeClass('is-invalid');
			};
			
		function setIdToEdit(id){
			
			if(id){
				$('#save_student').addClass("d-none");
				$('#edit_student').removeClass("d-none");
				$('.is-invalid').removeClass('is-invalid');
				let student_api_id = document.getElementById('student_api_id_'+id).getAttribute('student_api_id');
				setElementValue('student_api_id', student_api_id);
				let student_name = getElementInner('student_name_' + id);
				setElementValue('student_name', student_name);
				let student_surname = getElementInner('student_surname_' + id);
				setElementValue('student_surname', student_surname);
				let student_group_title = document.getElementById('student_group_title_'+id).getAttribute('student-group');
				$('#student_group_title').val(student_group_title);
			}
			
		};
		
		
			$(document).ready(function(){

			
			$('#save_student').click(function(){
				
				let student_name = $('#student_name').val();
				let student_surname = $('#student_surname').val();
				let student_group_title = $('#student_group_title').val();
				let student_project_title = $('#student_project_title').val();
				
		
				$.ajax({
					type: 'POST',
					url: "{{ config('app.api_students_link') }}",
					data: {csrf:csrf,student_name:student_name, student_surname:student_surname, student_group_title:student_group_title, student_project_title:student_project_title},
					success: function(data){
						
						$('#alert').removeClass("alert-success");
						$('#alert').removeClass("alert-danger");	
						
						if($.isEmptyObject(data.error_message)){
							$('#alert').removeClass("d-none");
							$('#alert').addClass("alert-success"); 
							$('#alert').html(data.success_message);
							
							$('#exampleModal').hide();
							$('body').removeClass('modal-open');
							$('.modal-backdrop').remove();
							$('body').css({overflow:'auto'});
							
							$('#student_name').val('');
							$('#student_surname').val('');
							$('#student_project_title').val('');
							location.reload();
						}else{						
							$('.is-invalid').removeClass('is-invalid');
							$.each(data.errors, function(key, error){
								console.log(key);
								$('#'+key).addClass('is-invalid');
								$('.input_'+key).html(error);
							});
						}
						

					}
					
				});
			});
			
			
			$('#edit_student').click(function(){
				
				let student_api_id = $('#student_api_id').val();
				let student_name = $('#student_name').val();
				let student_surname = $('#student_surname').val();
				let student_group_title = $('#student_group_title').val();
				let student_project_title = $('#student_project_title').val();
				
		
				$.ajax({
					type: 'PUT',
					url: "{{ config('app.api_students_link') }}/"+student_api_id,
					data: {csrf:csrf,student_name:student_name, student_surname:student_surname, student_group_title:student_group_title, student_project_title:student_project_title},
					success: function(data){
							$('#alert').removeClass("alert-success");
							$('#alert').removeClass("alert-danger");
						if($.isEmptyObject(data.error_message)){
							$('#alert').removeClass("d-none");
							$('#alert').addClass("alert-success"); 
							$('#alert').html(data.success_message);
							
							$('#exampleModal').hide();
							$('body').removeClass('modal-open');
							$('.modal-backdrop').remove();
							$('body').css({overflow:'auto'});
							
							$('#student_name').val('');
							$('#student_surname').val('');
							$('#student_group_title').val('');
							$('#student_project_title').val('');
							location.reload();
						}else{						
							$('.is-invalid').removeClass('is-invalid');
							$.each(data.errors, function(key, error){
								console.log(key);
								$('#'+key).addClass('is-invalid');
								$('.input_'+key).html(error);
							});
						}
					}
				});
			});
			
			$(document).on('click', '.delete-student', function(){	
			
				let	studentId = $(this).attr('student_api_id');
				$.ajax({
					type: 'DELETE',
					url: "{{ config('app.api_students_link') }}/"+studentId,
					data: {csrf:csrf},
					success: function(data){
							$('#alert').removeClass("alert-success");
							$('#alert').removeClass("alert-danger");
						if($.isEmptyObject(data.error_message)){
							$('#alert').removeClass("d-none");
							$('#alert').addClass("alert-success"); 
							$('#alert').html(data.success_message);
							location.reload();	
						}
					}
					
				});
			
			});			

			
			
				

				
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
						url: "{{ config('app.api_students_link') }}/"+student_id,
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
						url: "{{ config('app.api_students_link') }}/"+student_id,
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
				
				$(document).on('click', '#add_new_group', function(){	

					let project_id = $(this).attr('project-id');
					
					$.ajax({
						type: 'POST',
						url: "{{route('project.addgroup')}}",
						data: {project_id:project_id},
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