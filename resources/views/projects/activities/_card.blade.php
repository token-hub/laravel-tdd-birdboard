<div class="card">
	@foreach($activities as $activity)
		<ul class='text-sm'>
			<li>
				@component("projects.activities._$activity->description", ['activity' => $activity])
				@endcomponent
				<span class='text-gray-500'> - {{ $activity->updated_at->diffForHumans(null, true) }} </span>
			</li>
		</ul>
	@endforeach
</div>