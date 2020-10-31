@component('layouts.app')
	<div class='flex justify-between mb-3'>
		<h2>Birdboard</h2>
		<a href="/projects/create"><h2>Create project</h2></a>
	</div>
	<ul>
		@forelse($projects as $project)
			<li>
				<a href="{{ $project->path() }}">{{ $project->title }}</a>
			</li>
		@empty
			<li>No projects yet</li>
		@endforelse
	</ul>
@endcomponent