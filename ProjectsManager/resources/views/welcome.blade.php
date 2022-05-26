<x-head/>

<div class="container">
	<div class = "row justify-content-center homediv">
		<div class = "col-12">
			@if (Route::has('login'))
			<div class="row align-items-center homebuttons">
				@auth
					<div class="col homebuttdiv">
						<a href="{{ route('home') }}" class="pageLink">Log in</a>
					</div>
				@else
					<div class="col homebuttdiv">
						<a href="{{ route('login') }}" class="pageLink">Log in</a>
					</div>
				@if (Route::has('register'))
					<div class="col homebuttdiv">
						<a href="{{ route('register') }}" class="pageLink">Register</a>
					</div>
				@endif
				@endauth
			</div>
			@endif
		</div>
	</div>
</div>

<x-bottom/>

            

