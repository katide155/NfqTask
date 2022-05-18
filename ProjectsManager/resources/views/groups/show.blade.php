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
				  <div class="col-4">Title:</div>
				  <div class="col-6">{{$group->group_title}}</div>
				</div>
			  </div>
			  <div class="modal-body">
				<div class="row g-3 align-items-center">
				  <div class="col-4">Number:</div>
				  <div class="col-6">{{$group->group_number}}</div>
				</div>
			  </div>
				  <div class="modal-body">
				<div class="row g-3 align-items-center">
				  <div class="col-4">Grup students:</div>
				  <div class="col-6">
						@foreach($group->groupStudent as $student)
							<div class="row g-3 align-items-center">
								<div class="col-2">{{$student->id}}</div>
								<div class="col-4">{{$student->student_name}}</div>
								<div class="col-4">{{$student->student_surname}}</div>
								<div class="col-2">
								<form method="post" action="{{route('student.destroy', [$student])}}">
									@csrf
									<button class="btn btn-danger" type="submit">Delete</button>
								</form>	
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
				<a class="btn btn-success" href="{{route('group.edit',[$group])}}">Edita</a>
			</div>
	</div>
  </div>

</div>

@endsection