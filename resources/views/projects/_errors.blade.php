@if($errors->{$bag ?? 'default'}->any())
	<div class='field mt-6'>
		@foreach($errors->{$bag ?? 'default'}->all() as $error)
			<li class='text-sm text-red-400'>{{ $error }}</li>
		@endforeach
	</div>
@endif