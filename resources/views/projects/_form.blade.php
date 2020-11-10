<div>
	<label for="title">Title</label><br>
	<input type="text" id='title' name='title' value="{{$project->title}}" required>
</div>

<div>
	<label for="description">description</label><br>
	<textarea id='description' name='description' cols="30" rows="10" required> {{$project->description}} </textarea>
</div>

<div>
	<button type="submit">{{$buttonText}}</button>
</div>

<a href="/{{$project->path()}}">Cancel</a>