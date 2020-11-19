<div class='card flex flex-col'>
	<h3 class='font-normal text-2xl -ml-4 pl-4 border-l-4 border-blue-200 '>
		<a href="/{{$project->path()}}">
			{{ $project->title }}
		</a>
	</h3>
	<div class='text-gray-500 mb-4 flex-1'>{{ str($project->description) }}</div>

	<footer>
		<form action="{{ $project->path() }}" class='text-right' method='POST'>
			@method('DELETE')
			@csrf
			<button type='submit' class='button'>Delete</button>
		</form>
	</footer>
</div>