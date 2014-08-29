<div class="col-sm-6 col-md-4" id="media-{{$media->id}}" data-upload-image="{{$media->id}}">
    <div class="thumbnail">
      <img src="@media($media->id)" alt="...">
      <div class="caption">
        <p><span class="label label-default">{{$media->id}}</span></p>
        <p>
        	<button type="submit" class="btn btn-danger delete-image" data-media-id="{{ $media->id }}">Delete</button>	
          <button class="btn btn-default cover-image" role="button" data-media-id="{{ $media->id }}" data-toggle="button">Set Cover Image</button>
        </p>
      </div>
    </div>
  </div>