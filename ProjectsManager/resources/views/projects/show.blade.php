@extends('layouts.app')

@section('content')

<div class="container">

	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Project data</h5>
			</div>
			<div class="modal-body">
				<div class="row g-3 align-items-center">
				  <div class="col-7">
					Project title
				  </div>
				  <div class="col-5">
					{{$project->project_title}}
				  </div>
				</div>
				<div class="row g-3 align-items-center">
				  <div class="col-7">
					Number of groups
				  </div>
				  <div class="col-5">
					{{$project->number_of_groups}}
				  </div>
				</div>
				<div class="row g-3 align-items-center">
				  <div class="col-7">
					Max number students per group
				  </div>
				  <div class="col-5">
					 {{$project->max_number_students_in_group}}
				  </div>
				</div>
			</div>
			 
			<div class="modal-footer">
				<a class="btn btn-secondary" href="{{route('project.index')}}">Back to list</a>
			</div>
		</div>
	</div>

</div>

@endsection