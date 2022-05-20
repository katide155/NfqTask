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
							<a href="{{route('group.show',[$student->studentGroup])}}">{{$student->studentGroup->group_title}}</a>
						</td>
						<td>
						<div class="btn-container">
							<a  type="button" href="{{route('student.edit',[$student])}}" class="btn btn-success dbfl edit-item act-item">..<span class="tooltipas">Edit</span></a>
							<form method="post" action="{{route('student.destroy',[$student])}}" class="dbfl">
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
			{!! $students->links() !!}
			@endif
			
			
			@if(count($projectGroups) == 0)
				
			<p>There are no groups in this project</p>
			
			<p><a class="btn btn-success"  href="{{route('group.create')}}">Add new group</a></p>
			
			@else
			<h2>Project groups</h2>
				<p><a class="btn btn-success"  href="{{route('group.create')}}">Add new group</a></p>

				<div class="row">
					@foreach ($projectGroups as $group)
						<div class="col-sm-4 pb-5">
							<div class="card" style="width: 18rem;">
								<div class="card-header">
									{{$group->group_title}}
								</div>
								<ul class="list-group list-group-flush">
									@for($x = 1; $x <= $group->max_number_students_in_group; $x++)
									<li class="list-group-item">
										<select class="form-select form-select-sm" aria-label=".form-select-sm example">
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
		</div>
	</div>	
</div>
	
@endsection