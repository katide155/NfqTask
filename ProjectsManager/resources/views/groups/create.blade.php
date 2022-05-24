@extends('layouts.app')

@section('content')

<div class="container">

	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">

			@if(count($projects)==0)
			<div class="modal-body">
				<h5 class="modal-title">You can't create group, because there are no one project.</h5>
			</div>	
			<div class="modal-footer">	
				<a class="btn btn-success" href="{{route('project.create')}}">Go here to create a project</a>
			</div>			
			@else
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Group data</h5>
			</div>
			<form action="{{route('group.store')}}" method="POST">
				<div class="modal-body">
					<div class="row g-3 align-items-center">
					  <div class="col-6">
						<label for="group_title" class="col-form-label">Group title</label>
					  </div>
					  <div class="col-6">
						<input type="text" id="group_title" name="group_title" class="form-control @error('group_title') is-invalid @enderror" value="{{ old('group_title') }}">
						@error('group_title')
							<span class="invalid-feedback" role="alert">{{ $message }}</span>
						@enderror
					  </div>
					</div>
				</div>
				<div class="modal-body">
					<div class="row g-3 align-items-center">
					  <div class="col-6">
						<label for="group_project_id" class="col-form-label">Group project</label>
					  </div>
					  <div class="col-6">
						<select id="group_project_id" class="form-select @error('group_project_id') is-invalid @enderror" aria-label=".form-select-sm example" name="group_project_id">
							@foreach ($projects as $project)
								<option value="{{$project->id}}">{{$project->project_title}}</option>
							@endforeach
						</select>
						@error('group_project_id')
							<span class="invalid-feedback" role="alert">{{ $message }}</span>
						@enderror
					  </div>
					</div>
				</div>

				@csrf  
				<div class="modal-footer">
					<a class="btn btn-secondary" href="{{route('group.index')}}">Back</a>
					<button class="btn btn-success" type="submit" name="save_group">Save</button>
				</div>
			</form>
			@endif
		</div>
	</div>

</div>

@endsection