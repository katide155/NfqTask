@extends('layouts.app')

@section('content')

<div class="container">
	<div class="row">
		<div class="col-12">
			<h2>Group list</h2>
		</div>
		
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
	

		

			@if(count($groups) == 0)
				
			<p>There are no groups here</p>
			
			<p><a class="btn btn-success"  href="{{route('group.create')}}">Create new group</a></p>
			
			@else
				
			<table class="table table-success table-striped">

			<thead>
			  <tr>
				<th>Row. No.</th>
				<th>Group title</th>
				<th>Maximum number of students</th>
				<th>Number of students</th>
				<th>Group project</th>
				<th style="width: 180px;">
					<a type="button" class="btn btn-success" href="{{route('group.create')}}">Add group</a>
				</th>
			  </tr>
			</thead>
			<tbody>
			<?php $i=1; ?>
			@foreach ($groups as $group)
			  <tr>
				<td>{{ $i++; }}</td>
				<td>{{$group->group_title}}</td>
				<td>{{$group->max_number_students_in_group}}</td>
				<td>{{$group->group_students}}</td>
				<td>{{$group->groupProject->project_title}}</td>
				<td>
					<div class="btn-container">
						<a type="button" href="{{route('group.edit',[$group])}}" class="btn btn-success dbfl edit-item act-item">
							..<span class="tooltipas">Edit</span>
						</a>

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

		
			<script>

			</script>

			@endif
			
				
		</div>
	</div>	
</div>	
@endsection