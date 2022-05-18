@extends('layouts.app')

@section('content')

<div class="container">

  <div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="exampleModalLabel">Grupės duomenys</h5>
	  </div>
	<form action="{{route('group.update', [$group])}}" method="POST">
	  <div class="modal-body">
		<div class="row g-3 align-items-center">
		  <div class="col-4">
			<label for="group_title" class="col-form-label">Grupės pavadinimas</label>
		  </div>
		  <div class="col-6">
			<input type="text" id="group_title" name="group_title" class="form-control" value="{{$group->group_title}}">
		  </div>
		</div>
	  </div>
	  <div class="modal-body">
		<div class="row g-3 align-items-center">
		  <div class="col-4">
			<label for="group_number" class="col-form-label">Grupės numeris</label>
		  </div>
		  <div class="col-6">
			<input type="text" id="group_number" name="group_number" class="form-control" value="{{$group->group_number}}">
		  </div>
		</div>
	  </div>

	@csrf  
	  <div class="modal-footer">
		<a class="btn btn-secondary" href="{{route('group.index')}}">Grįžti</a>
		<button class="btn btn-success" type="submit" name="save_group">Saugoti</button>
	  </div>
	</form>
</div>
</div>

</div>

@endsection