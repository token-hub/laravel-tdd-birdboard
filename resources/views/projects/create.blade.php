@component('layouts.app')
	<h2>Create project</h2>
	<form action="/projects" method="POST">
		@csrf

		<div>
			<label for="title">Title</label><br>
			<input type="text" id='title' name='title'>
		</div>

		<div>
			<label for="description">description</label><br>
			<textarea id='description' name='description' cols="30" rows="10"></textarea>
		</div>

		<div>
			<button type="submit">Submit</button>
		</div>
	</form>
	<a href="/projects">Cancel</a>
@endcomponent