<div class='card'>
	<h3 class='font-normal text-2xl -ml-4 pl-4 border-l-4 border-blue-200 '>
		<a href="/{{$project->path()}}">
			{{ $project->title }}
		</a>
	</h3>
	<div class='text-gray-500'>{{ str($project->description) }}</div>
</div>