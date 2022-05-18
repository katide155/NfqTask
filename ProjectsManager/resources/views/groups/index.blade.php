@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-12">
			<h2>Group list</h2>
		</div>
	</div>
	<div class="row">
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
		<div class="col-12">


			<table class="table table-success table-striped">

			<thead>
			  <tr>
				<th>Row. No.</th>
				<th>Group title</th>
				<th>Maximum number of students per group</th>
				<th>Group students</th>
				<th>Group project</th>
				<th style="width: 180px;">
					<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearValues()">Add group</button>
				</th>
			  </tr>
			</thead>
			<tbody>
			<?php $i=1; ?>
			@foreach ($groups as $group)
			  <tr>
				<td>{{ $i++; }}</td>
				<td><div id="group_title_{{$group->id}}">{{$group->group_title}}</td>
				<td><div id="group_title_{{$group->id}}">{{$group->max_number_students_in_group}}</td>
				<td><div id="group_title_{{$group->id}}">{{count($group->groupStudents)}}</td>
				<td><div id="group_title_{{$group->id}}">{{$group->groupProject->project_title}}</td>
				<td>
					<div class="btn-container">
						<button type="button" class="btn btn-success dbfl edit-item act-item" data-bs-id="1" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="setIdToEdit({{$group->id}})">
							..<span class="tooltipas">Edit</span>
						</button>

						<form method="post" action="{{route('group.destroy',[$group,$page='index'])}}" class="dbfl">
							@csrf
							<button type="submit" name="delete_child" class="btn btn-dangeris dbfl delete-item act-item">x<span class="tooltipas">Delete</span></button>
						</form>
						<a  type="button" href="{{route('group.show',[$group])}}" class="btn btn-primary dbfl show-item act-item"><span class="tooltipas">Show</span></a>
					</div>
				</td>
			  </tr>
			  
			@endforeach

			</tbody>
			</table>
				<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
					  <div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Group data</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					  </div>
						<form action="{{route('group.store')}}" method="POST" id="group_form">
						  <div class="modal-body">
							<div class="row g-3 align-items-center">
							  <div class="col-4">
								<label for="group_title" class="col-form-label">Group title</label>
							  </div>
							  <div class="col-6">
								<input type="text" id="group_title" name="group_title" class="form-control" aria-describedby="passwordHelpInline">
							  </div>
							</div>
						  </div>
						  <div class="modal-body">
							<div class="row g-3 align-items-center">
							  <div class="col-4">
								<label for="max_number_students_in_group" class="col-form-label">Max number of students</label>
							  </div>
							  <div class="col-6">
								<input type="number" id="max_number_students_in_group" name="max_number_students_in_group" class="form-control" aria-describedby="passwordHelpInline">
							  </div>
							</div>
						  </div>

						@csrf  
						  <div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
							<button class="btn btn-success" type="submit" name="save_group">Save</button>
						  </div>
						</form>
					</div>
				  </div>
				</div>
		
			<script>
	
			
			function clearValues(){
				setElementValue('group_title', '');
				setElementValue('group_number', '');
				changeFormAction2('group_form');
			}
			

			
			function setIdToEdit(id){
				
				if(id){
					let group_title = getElementInner('group_title_' + id);
					setElementValue('group_title', group_title);
					let group_number = getElementInner('group_number_' + id);
					setElementValue('group_number', group_number);
					changeFormAction2('group_form', id);
				}
				
			}
			</script>


		</div>
	</div>	
</div>	
@endsection