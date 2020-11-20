@component('layouts.app')
	<header class='flex justify-between mb-6'>
		<div class='flex items-end'>
			<p class="text-default mr-2">
				<a href="/projects">My Projects</a> / {{ $project->title }}
			</p>
			<a
				href="/{{$project->path()}}/edit"
				class="button"
				><h2>Edit project</h2>
			</a>
		</div>
		<div class="flex items-center">
			@forelse($project->members as $member)
				<img src="https://gravatar.com/avatar/{{ md5($member->email)}}?s=60" class='rounded-full w-8 m-2' alt="{{$member->name}}' avatar">
			@empty
			@endforelse

			<a
				href="/projects/create"
				class="button ml-4"
				><h2>Invite to project</h2>
			</a>

		</div>
	</header>

	<main class='lg:flex'>
		<div class='lg:w-3/4 px-4'>
			<!-- Task -->
			<h2 class='text-default py-3'>Tasks</h2>

				<div class='card'>
					<form action="/{{$project->path()}}/tasks" method="POST">
						@csrf
						<label for="">@error('body') {{ $errors->first('body') }} @enderror</label>
						<input type="text" class="w-full bg-card text-default" placeholder="Begin adding task" name="body" value="{{old('body')}}">
					</form>
				</div>

				@foreach($project->tasks as $task)
					<form action="{{$task->path()}}" method="POST">
						@method('PATCH')
						@csrf
						<div class='card'>
							<div class="flex">
								<input type="text" name='body' class="w-full {{ $task->completed ? 'text-gray-500 strike line-through' : 'text-default' }} bg-card" value="{{ $task->body }}">
								<input type="checkbox" name="completed" onChange="this.form.submit()"  {{ $task->completed ? 'checked' : '' }}>
							</div>
						</div>
					</form>
				@endforeach
			<!-- General notes -->
			<h2 class='text-default py-3'>General Notes</h2>

			<div>
				<form action="/{{$project->path()}}" method='POST'>
					@csrf
					@method('PATCH')
					<textarea
						class='card w-full'
						placeholder="Create notes for your project"
						name='notes'
						style='min-height: 200px'
						>{{ $project->notes }}
					</textarea>
					<button type='submit' class='button'>Submit</button>
				</form>
			</div>
			@include('projects._errors')
		</div>

		<div class='lg:w-1/4'>
			@include('projects._card', ['project' => $project])
			@include('projects.activities._card', ['activities' => $project->activities->take(10)])

			@can('manage', $project)
				@include('projects._invite', ['project' =>  $project])
			@endcan
		</div>
	</main>
@endcomponent