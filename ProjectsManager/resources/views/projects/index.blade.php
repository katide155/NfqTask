@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-12">
			<h2>Projects list</h2>
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
			
			@if(count($projects) == 0)
				
			<p>There are no projects here</p>
			
			<p><a class="btn btn-success"  href="{{route('project.create')}}">Create new project</a></p>
			
			@else

			<table class="table table-success table-striped">

			<thead>
			  <tr>
				<th>Row No.</th>
				<th>Project title</th>
				<th>Project groups</th>
				<th>Students per group</th>				
				<th>Maximum number of students in project</th>
				<th>Project students</th>
				<th style="width: 180px;">
					<a  type="button" href="{{route('project.create')}}" class="btn btn-success">Add new project</a>
				</th>
			  </tr>
			</thead>
			<tbody>
			
			
			<?php $i=1; ?>
			@foreach ($projects as $project)
			  <tr>
				<td>{{ $i++; }}</td>
				<td><div id="project_title_{{$project->id}}">{{$project->project_title}}</div></td>
				<td><div>{{count($project->projectGroups)}}<div></td>
				<td><div id="project_max_student_number_{{$project->id}}">{{$project->max_number_students_in_group}}</div></td>
				<td><div id="project_max_student_number_{{$project->id}}">{{$project->max_number_students_in_group * $project->number_of_groups}}</div></td>
				<td><div id="project_students_number_{{$project->id}}">{{$project->project_students}}</div></td>
				<td>
					<div class="btn-container">
						<a  type="button" href="{{route('project.edit',[$project])}}" class="btn btn-success dbfl edit-item act-item">..<span class="tooltipas">Edit</span></a>
						<form method="post" action="{{route('project.destroy',[$project])}}" class="dbfl">
							@csrf
							<button type="submit" name="delete_project" class="btn btn-dangeris dbfl delete-item act-item">x<span class="tooltipas">Delete</span></button>
						</form>
						<a  type="button" href="{{route('project.show',[$project])}}" class="btn btn-primary dbfl show-item act-item"><span class="tooltipas">Show</span></a>
						<a  type="button" href="{{route('project.status',[$project])}}" class="btn btn-warning dbfl status-item act-item"><span class="tooltipas">Status</span></a>
					</div>
				</td>
			  </tr>
			  
			@endforeach

			</tbody>
			</table>
			{!! $projects->links() !!}
			@endif
		</div>
	</div>	
</div>
	
@endsection