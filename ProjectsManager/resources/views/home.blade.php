@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Welcome to "Projects manager"!') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in! Now you can add or manage your projects and students lists.') }}
                </div>
            </div>
        </div>
	</div>
	<div class = "row justify-content-center homediv">
		<div class = "col-12">	
			<div class="row align-items-center homebuttons">
				<div class="col homebuttdiv">
					<a href="{{ route('project.create') }}" class="pageLink">Create new project</a>
				</div>
				<div class="col homebuttdiv">
					<a href="{{ route('project.index') }}" class="pageLink">Projects list</a>
				</div>
				<div class="col homebuttdiv">
					<a href="{{ route('student.index') }}"  class="pageLink">Students list</a>
				</div>
			</div>
		</div>
    </div>
</div>
@endsection
