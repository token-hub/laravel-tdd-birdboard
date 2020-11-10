@component('layouts.app')
	<h2>Edit your project</h2>
	<form action="/{{$project->path()}}" method="POST">
		@csrf
		@method('PATCH')
		@component('projects._form', ['project' => $project, 'buttonText' => 'Update Project'])
		@endcomponent
	</form>
@endcomponent
