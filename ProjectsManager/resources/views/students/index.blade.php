@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<h2>Students list</h2>
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
			
			@if(0)
				<p>No students are on the list</p>
				{{-- <p><a href="{{route('student.create')}}">Sukurti naują įrašą</a></p> --}}
			@else
				
			<table class="table table-success table-striped" id="students">
			
				<thead>
					<tr>
						<th>Name</th>
						<th>Surname</th>
						<th>Student group</th>
						<th style="width: 180px;">
							<button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal" onclick="clearValues()">Add new student</button>
						</th>
					</tr>
				</thead>
				
				<tbody>

					<tr>
						<td><div></div></td>
						<td><div></div></td>
						<td><div></div></td>
						<td>
							<div class="btn-container">
								<button type="button" class="btn btn-success" data-bs-id="1" data-bs-toggle="modal" data-bs-target="#exampleModal">Edit</button>
								<button type="submit" class="btn btn-danger">Delete</button>
								<button type="submit" class="btn btn-primary">Show</button>
							</div>
						</td>
					</tr>

				</tbody>
				
			</table>
			@endif
		</div>
	</div>
</div>

	<script>
		$(document).ready(function(){
			console.log('yra');
			//$.ajax();
		})
	</script>

@endsection