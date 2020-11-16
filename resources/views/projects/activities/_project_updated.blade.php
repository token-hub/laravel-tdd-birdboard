@if(count($activity->changes['after']) == 1 )
	{{ $activity->ownerName() }} updated {{ key($activity->changes['after']) }} of the project
@else
	You updated the project
@endif