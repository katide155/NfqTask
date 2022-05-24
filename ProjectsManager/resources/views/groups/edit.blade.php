@extends('layouts.app')

@section('content')

<div class="container">

  <div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel">Group data</h5>
	  </div>
	<form action="{{route('group.update', [$group])}}" method="POST">
	  <div class="modal-body">
		<div class="row g-3 align-items-center">
		  <div class="col-6">
			<label for="group_title" class="col-form-label">Group title</label>
		  </div>
		  <div class="col-6">
			<input type="text" id="group_title" name="group_title" class="form-control @error('group_title') is-invalid @enderror" value="{{ $group->group_title }}">
			@error('group_title')
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
</div>
</div>

</div>

@endsection