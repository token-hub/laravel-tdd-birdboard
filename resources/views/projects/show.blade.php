@component('layouts.app')

	<header class='flex justify-between mb-6'>
		<div class='flex items-end'>
			<p class="text-gray-500 mr-2">
				<a href="/projects">My Projects</a> / {{ $project->title }}
			</p>
			<a
				href="/{{$project->path()}}/edit"
				class="button"
				><h2>Edit project</h2>
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
					<form action="{{$task->path()}}" method="POST">
						@method('PATCH')
						@csrf
						<div class='card'>
							<div class="flex">
								<input type="text" name='body' class="w-full {{ $task->completed ? 'text-gray-400' : '' }} " value="{{ $task->body }}">
								<input type="checkbox" name="completed" onChange="this.form.submit()"  {{ $task->completed ? 'checked' : '' }}>
							</div>
						</div>
					</form>
				@endforeach
			<!-- General notes -->
			<h2 class='text-gray-500 py-3'>General Notes</h2>

			<div>
				<form action="/{{$project->path()}}" method='POST'>
					@csrf
					@method('PATCH')
					<textarea
						class='card w-full mx-2'
						style="height: 200px"
						placeholder="Create notes for your project" name='notes'
						>{{ $project->notes }}
					</textarea>
					<button type='submit' class='button'>Submit</button>
				</form>
			</div>
		</div>

		<div class='lg:w-1/4'>
			@component('projects._card', ['project' => $project])
			@endcomponent

			@component('projects.activities._card', ['activities' => $project->activities])
			@endcomponent
		</div>
	</main>
@endcomponent