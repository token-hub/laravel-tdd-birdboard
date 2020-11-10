@component('layouts.app')
	<h2>Create project</h2>
	<form action="/projects" method="POST">
		@csrf
		@component('projects._form', ['project' => new App\Project, 'buttonText' => 'Create Project'])
		@endcomponent
	</form>
@endcomponent