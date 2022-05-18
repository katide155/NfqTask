@extends('layouts.app')

@section('content')

<div class="container">

	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Project data</h5>
			</div>
			<form action="{{route('project.update', [$project])}}" method="POST">
				<div class="modal-body">
					<div class="row g-3 align-items-center">
					  <div class="col-5">
						<label for="project_title" class="col-form-label">Project title</label>
					  </div>
					  <div class="col-7">
						<input type="text" id="project_title" name="project_title" class="form-control @error('project_title') is-invalid @enderror" value="{{$project->project_title}}">
						@error('project_title')
							<span class="invalid-feedback" role="alert">{{ $message }}</span>
						@enderror
					  </div>
					</div>
				</div>
				<div class="modal-body">
					<div class="row g-3 align-items-center">
					  <div class="col-5">
						<label for="number_of_groups" class="col-form-label">Number of groups</label>
					  </div>
					  <div class="col-7">
						<input type="number" id="number_of_groups" name="number_of_groups" class="form-control @error('number_of_groups') is-invalid @enderror" value="{{$project->number_of_groups}}">
						@error('number_of_groups')
							<span class="invalid-feedback" role="alert">{{ $message }}</span>
						@enderror
					  </div>
					</div>
				</div>
				<div class="modal-body">
					<div class="row g-3 align-items-center">
					  <div class="col-5">
						<label for="max_number_students_in_group" class="col-form-label">Max number students per group</label>
					  </div>
					  <div class="col-7">
						<input type="number" id="max_number_students_in_group" name="max_number_students_in_group" class="form-control @error('max_number_students_in_group') is-invalid @enderror" value="{{$project->max_number_students_in_group}}">
						@error('max_number_students_in_group')
							<span class="invalid-feedback" role="alert">{{ $message }}</span>
						@enderror
					  </div>
					</div>
				</div>
				@csrf  
				<div class="modal-footer">
					<a class="btn btn-secondary" href="{{route('project.index')}}">Back</a>
					<button class="btn btn-success" type="submit" name="save_project">Save</button>
				</div>
			</form>
		</div>
	</div>

</div>

@endsection