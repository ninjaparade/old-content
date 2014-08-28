<div class="col-sm-6 col-md-4" id="media-{{$media->id}}">
    <div class="thumbnail">
      <img src="@media($media->id)" alt="...">
      <div class="caption">
        <p><span class="label label-default">{{$media->id}}</span></p>
        <p>{{$media->name}}</p>
        <p>
        	
			<button type="submit" class="btn btn-danger delete-image" data-media-id="{{ $media->id }}">Delete</button>	
        	
        <a href="#" class="btn btn-default" role="button">Button</a></p>
      </div>
    </div>
  </div>