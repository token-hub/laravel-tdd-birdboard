@component('layouts.app')
	<header class='flex justify-between mb-3 items-end'>
		<h2 class="text-gray-500">My Projects</h2>
		<a
			href="/projects/create"
			class="button"
			><h2>New project</h2>
		</a>
	</header>

	<main class='lg:flex lg:flex-wrap -mx-3'>
		@forelse($projects as $project)
			<div class='lg:w-1/3'>
				@component('projects.card', ['project' => $project])
				@endcomponent
			</div>
		@empty
			<div>No projects yet</div>
		@endforelse
	</main>
@endcomponent