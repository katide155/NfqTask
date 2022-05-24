@extends('layouts.app')

@section('content')

<div class="container">
 <div class="modal-dialog modal-dialog-centered row">
	@if(session()->has('error_message'))
		<div class="alert alert-danger">
			{{session()->get('error_message')}}
		</div>
	@endif
	@if(session()->has('success_message'))
		<div class="alert alert-success">
			{{session()->get('success_message')}}
		</div>
	@endif
 </div>
  <div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel">Group data</h5>
	  </div>

			  <div class="modal-body">
				<div class="row g-3 align-items-center">
				  <div class="col-2">Title:</div>
				  <div class="col-10"><h4>{{$group->group_title}}</h4></div>
				</div>
			  </div>
				  <div class="modal-body">
				<div class="row g-3 align-items-center">
				  <div class="col-12"><b>Group students:</b></div>
				  <div class="col-12">
						@foreach($groupStudents as $student)
							<div class="row g-3 align-items-center">
								<div class="col-5">{{$student->student_name}} {{$student->student_surname}}</div>
								<div class="col-3">

									<button type="button" class="btn btn-dangeris dbfl delete-student act-item" student_api_id="{{$student->api_student_id}}">x<span class="tooltipas">Delete</span></button>
									<button type="button" class="btn btn-primary dbfl remove-student act-item" student="{{$student['student_name']}} {{$student['student_surname']}}" student-id="{{$student->api_student_id}}">r<span class="tooltipas">Remove</span></button>
								</div>
							</div>
						@endforeach
				  </div>
				</div>
			  </div>
			<div class="modal-footer">
	 
				<a class="btn btn-secondary" href="{{route('group.index')}}">Back</a>
				
			    <form method="post" action="{{route('group.destroy', [$group,$page='show'])}}">
					@csrf
					<button class="btn btn-danger" type="submit">Delete</button>
				</form>
				<a class="btn btn-success" href="{{route('group.edit',[$group])}}">Edit</a>
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
			
			
			$(document).ready(function(){
			
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
				
				
			});

</script>
@endsection