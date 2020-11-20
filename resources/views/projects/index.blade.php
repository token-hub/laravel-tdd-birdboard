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
				@include('projects._card', ['project' => $project])
			</div>
		@empty
			<div>No projects yet</div>
		@endforelse
	</main>

	<new-project-modal></new-project-modal>


	<a href="" class='button' @click.prevent="$modal.show('projectmodal')"> Show Modal </a>
@endcomponent