@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<h2>Students list</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
		
				<div id="alert" class="alert d-none">
				</div>
			
			@if(!count($students))
				<p>No students are on the list</p>
				{{-- <p><a href="{{route('student.create')}}">Sukurti naują įrašą</a></p> --}}
			@else
				
			<table class="table table-success table-striped" id="students">
			
				<thead>
					<tr>
						<th>Name</th>
						<th>Surname</th>
						<th>Student group</th>
						<th>Student project</th>
						<th style="width: 180px;">
							<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearValues()">Add new student</button>
						</th>
					</tr>
				</thead>
				
				<tbody>
				@foreach($students as $student)
					<tr>
						<td>
							<input type="hidden" id="student_api_id_{{$student->id}}" student_api_id="{{$student->api_student_id}}" />
							<div id="student_name_{{$student->id}}">{{$student->student_name}}</div>
						</td>
						<td><div id="student_surname_{{$student->id}}">{{$student->student_surname}}</div></td>
						<td>
							<div id="student_group_title_{{$student->id}}">
									{{$student->student_group_title}}
							</div>
						</td>
						<td>
							<div id="student_project_title_{{$student->id}}">
									{{$student->student_project_title}}							
							</div>
						</td>
						<td>
							<div class="btn-container">
								<button type="button" class="btn btn-success dbfl edit-item act-item" data-bs-id="1" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="setIdToEdit({{$student->id}})">
									..<span class="tooltipas">Edit</span>
								</button>
								<button type="submit" name="delete_child" class="btn btn-dangeris dbfl delete-student act-item" student_api_id="{{$student->api_student_id}}">x<span class="tooltipas">Delete</span></button>
								<a  type="button" href="{{route('student.show',[$student])}}" class="btn btn-primary dbfl show-item act-item"><span class="tooltipas">Show</span></a>
							</div>
						</td>
					</tr>
				@endforeach
				</tbody>
				
			</table>
			{!! $students->links() !!}
			
			
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
									<select id="student_group_title" class="form-control" aria-label=".form-select-sm example" name="student_group_title">
										@foreach ($groups as $group)
										<option value="{{$group->group_title}}">{{$group->group_title}}</option>
										@endforeach
									</select>
									<span class="invalid-feedback input_student_group_title" role="alert"></span>
								</div>
							</div>
						</div>
						<div class="modal-body">
							<div class="row g-3 align-items-center">
							  <div class="col-4">
								<label for="student_project_title" class="col-form-label">Student project</label>
							  </div>
								<div class="col-8">
									<select id="student_project_title" class="form-control" aria-label=".form-select-sm example" name="student_project_title">
										@foreach ($projects as $project)
										<option value="{{$project->project_title}}">{{$project->project_title}}</option>
										@endforeach
									</select>
									<span class="invalid-feedback input_student_project_title" role="alert"></span>
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
			
			@endif
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
			setElementValue('student_project_title', '');
			document.getElementById('student_project_title').selected = false;
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
				let student_group_title = getElementValue('student_group_title_' + id);
				document.getElementById('student_group_title').value = student_group_title;
				let student_project_title = getElementValue('student_project_title_' + id);
				document.getElementById('student_project_title').value = student_project_title;
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
					url: 'http://127.0.0.1:8080/api/students',
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
				console.log(student_api_id);
		
				$.ajax({
					type: 'PUT',
					url: 'http://127.0.0.1:8080/api/students/'+student_api_id,
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
					url: 'http://127.0.0.1:8080/api/students/'+studentId,
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
			

		});
	</script>

@endsection