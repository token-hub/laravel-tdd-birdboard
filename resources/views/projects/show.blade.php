@component('layouts.app')

	<header class='flex justify-between mb-6'>
		<div class='flex items-end'>
			<p class="text-gray-500 mr-2">
				<a href="/projects">My Projects</a> / {{ $project->title }}
			</p>
			<a
				href="/projects/create"
				class="button"
				><h2>New project</h2>
			</a>
		</div>
		<div class="flex">
			<a
				href="/projects/create"
				class="button"
				><h2>Invite to project</h2>
			</a>
		</div>
	</header>

	<main class='lg:flex'>
		<div class='lg:w-3/4 '>
			<!-- Task -->
			<h2 class='text-gray-500 py-3'>Tasks</h2>

				<div class='card'>
					<form action="/{{$project->path()}}/tasks" method="POST">
						@csrf
						<label for="">@error('body') {{ $errors->first('body') }} @enderror</label>
						<input type="text" class="w-full" placeholder="Begin adding task" name="body" value="{{old('body')}}">
					</form>
				</div>

				@foreach($project->tasks as $task)
					<div class='card'>{{ $task->body }}</div>
				@endforeach
			<!-- General notes -->
			<h2 class='text-gray-500 py-3'>General Notes</h2>

			<textarea class='card w-full mx-2' style="height: 200px">Lorem</textarea>
		</div>

		<div class='lg:w-1/4'>
			@component('projects.card', ['project' => $project])
			@endcomponent
		</div>
	</main>
@endcomponent