<div class='card flex flex-col'>
	<h3 class='font-normal text-2xl -ml-4 pl-4 border-l-4 border-blue-200 mb-3'>
		<a href="/{{$project->path()}}" >
			Invite a user
		</a>
	</h3>

	<form action="/{{ $project->path() }}/invitations" class='text-right' method='POST'>
		@csrf
		<input type="text" name='email' class='border border-gray-400 rounded mb-3 w-full py-2 px-3' placeholder="Email Address">
		<button type='submit' class='button'>Invite</button>
	</form>

	@include('projects._errors', ['bag' => 'invitations'])
</div>