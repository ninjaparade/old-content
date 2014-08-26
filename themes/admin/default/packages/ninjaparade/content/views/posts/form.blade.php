@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@parent
	: {{{ trans("ninjaparade/content::posts/general.{$mode}") }}} {{{ $post->exists ? '- ' . $post->name : null }}}
@stop

{{-- Queue assets --}}
{{ Asset::queue('dropzone.css', 'platform/media::css/dropzone.css') }}
{{ Asset::queue('imperavi.redactor.css', 'imperavi/css/redactor.css') }}
{{ Asset::queue('selectize.css', 'selectize/css/selectize.css', 'styles') }}

{{ Asset::queue('bootstrap.tabs', 'bootstrap/js/tab.js', 'jquery') }}
{{ Asset::queue('bootstrap.modal', 'bootstrap/js/modal.js', 'jquery') }}
{{ Asset::queue('content', 'ninjaparade/content::js/script.js', ['jquery', 'dropzone', 'imperavi.redactor']) }}

{{ Asset::queue('imperavi.redactor.js', 'imperavi/js/redactor.min.js', 'jquery') }}
{{ Asset::queue('platform.slugify.js', 'platform/js/slugify.js', 'jquery') }}
{{ Asset::queue('selectize.js', 'selectize/js/selectize.js', 'jquery') }}
{{ Asset::queue('dropzone.js', 'platform/media::js/dropzone/dropzone.js') }}
{{ Asset::queue('mediamanager', 'platform/media::js/mediamanager.js', ['jquery', 'dropzone']) }}
{{-- Inline scripts --}}
@section('scripts')
<script>
	$(function() {

		var app = {
			_token : "{{csrf_token()}}",
			url : "{{URL::route('post.get_media')}}"
		};

		$.mediamanager('#mediaUploader', {
			onSuccess : function(response)
			{
				$('#uploaded-media-row').append(response);
			}
		});

	});
</script>
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

@section('breadcrumb')
<li><a href="{{URL::route('posts.index')}}">All Posts</a></li>
<li>{{Str::title($mode)}} Post</li>
@stop

{{-- Page content --}}
@section('content')

{{-- Page header --}}
<div class="page-header">

	<h1>{{{ trans("ninjaparade/content::posts/general.{$mode}") }}} <small>{{{ $post->name }}}</small></h1>

</div>

{{-- Content form --}}
<form id="content-form" action="{{ Request::fullUrl() }}" method="post" accept-char="UTF-8" autocomplete="off">
				
	{{-- CSRF Token --}}
	<input type="hidden" name="_token" value="{{ csrf_token() }}">

	{{-- Tabs --}}
	<ul class="nav nav-tabs">
		<li class="active"><a href="#general" data-toggle="tab">{{{ trans('ninjaparade/content::general.tabs.general') }}}</a></li>
		<li><a href="#attributes" data-toggle="tab">{{{ trans('ninjaparade/content::general.tabs.attributes') }}}</a></li>
		<li><a href="#media" data-toggle="tab">Media</a></li>
	</ul>

	{{-- Tabs content --}}
	<div class="tab-content tab-bordered">

		{{-- General tab --}}
		<div class="tab-pane active" id="general">

			<div class="row">
				<div class="form-group{{ $errors->first('title', ' has-error') }}">

					<label for="title" class="control-label">{{{ trans('ninjaparade/content::posts/form.title') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.title_help') }}}"></i></label>

					<input type="text" class="form-control" name="title" id="title" placeholder="{{{ trans('ninjaparade/content::posts/form.title') }}}" value="{{{ Input::old('title', $post->title) }}}">

					<span class="help-block">{{{ $errors->first('title', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('slug', ' has-error') }}">

					<label for="slug" class="control-label">{{{ trans('ninjaparade/content::posts/form.slug') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.slug_help') }}}"></i></label>

					<input type="text" class="form-control" name="slug" id="slug" placeholder="{{{ trans('ninjaparade/content::posts/form.slug') }}}" value="{{{ Input::old('slug', $post->slug) }}}">

					<span class="help-block">{{{ $errors->first('slug', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('content', ' has-error') }}">

					<label for="content" class="control-label">{{{ trans('ninjaparade/content::posts/form.content') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.content_help') }}}"></i></label>

					<textarea class="form-control redactor" name="content" id="content" placeholder="{{{ trans('ninjaparade/content::posts/form.content') }}}">{{{ Input::old('content', $post->content) }}}</textarea>

					<span class="help-block">{{{ $errors->first('content', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('author_id', ' has-error') }}">

					<label for="author_id" class="control-label">{{{ trans('ninjaparade/content::posts/form.author_id') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.author_id_help') }}}"></i></label>

					<select class="form-control" name="author_id" id="author_id" required>
						@foreach ($authors as $author)
							<option value="{{$author->id}}" {{ Input::old('author', $author->id) == $post->author_id ? ' selected="selected"' : null }}>{{$author->name}}</option>
						@endforeach	
					</select>

					<span class="help-block">{{{ $errors->first('author_id', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('post_type', ' has-error') }}">

					<label for="post_type" class="control-label">{{{ trans('ninjaparade/content::posts/form.post_type') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.post_type_help') }}}"></i></label>


					<select class="form-control" name="post_type" id="post_type" required>
						@foreach ($posttypes as $posttype)
							<option value="{{$posttype->slug}}" {{ Input::old('post_type', $posttype->slug) == $post->post_type ? ' selected="selected"' : null }}>{{$posttype->title}}</option>
						@endforeach	
					</select>

					<span class="help-block">{{{ $errors->first('post_type', ':message') }}}</span>

				</div>


				<div class="form-group{{ $errors->first('pullquote', ' has-error') }}">

					<label for="pullquote" class="control-label">{{{ trans('ninjaparade/content::posts/form.pullquote') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.pullquote_help') }}}"></i></label>

					<textarea type="text" class="form-control redactor" name="pullquote" id="pullquote" placeholder="{{{ trans('ninjaparade/content::posts/form.pullquote') }}}" value="{{{ Input::old('pullquote', $post->pullquote) }}}"></textarea>

					<span class="help-block">{{{ $errors->first('pullquote', ':message') }}}</span>

				</div>

				

				<div class="form-group{{ $errors->first('tags', ' has-error') }}">

					<label for="publish_status" class="control-label">{{{ trans('ninjaparade/content::posts/form.tags') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.tags_help') }}}"></i></label>

					<select class="form-control" name="tags[]" id="tags" required multiple>
						@foreach ($post->tags as $tag)
							<option value="{{$tag->name}}" selected="selected">{{$tag->name}}</option>
						@endforeach
						 @foreach ($tags as $tag)
                			<option value="{{$tag->name}}">{{$tag->name}}</option>
           				@endforeach
					</select>

					<span class="help-block">{{{ $errors->first('publish_status', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('category', ' has-error') }}">

					<label for="post_type" class="control-label">{{{ trans('ninjaparade/content::posts/form.category') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.category_help') }}}"></i></label>


					<select class="form-control" name="category" id="category" required>
						@foreach ($categories as $category)
							<option value="{{$category->slug}}" {{ Input::old('category', $category->slug) == $post->category ? ' selected="selected"' : null }}>{{$category->name}}</option>
						@endforeach	
					</select>

					<span class="help-block">{{{ $errors->first('post_type', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('publish_status', ' has-error') }}">

					<label for="publish_status" class="control-label">{{{ trans('ninjaparade/content::posts/form.publish_status') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.publish_status_help') }}}"></i></label>

					<select class="form-control" name="publish_status" id="publish_status" required>
							<option value="0" {{ Input::old('publish_status', 0) == $post->publish_status ? ' selected="selected"' : null }}>Draft</option>
							<option value="1" {{ Input::old('publish_status', 1) == $post->publish_status ? ' selected="selected"' : null }}>Published</option>
					</select>

					<span class="help-block">{{{ $errors->first('publish_status', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('private', ' has-error') }}">

					<label for="private" class="control-label">{{{ trans('ninjaparade/content::posts/form.private') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.private_help') }}}"></i></label>

					<select class="form-control" name="private" id="private" required>
							<option value="0" {{ Input::old('private', 0) == $post->private ? ' selected="selected"' : null }}>Public</option>
							<option value="1" {{ Input::old('private', 1) == $post->private ? ' selected="selected"' : null }}>Private</option>
					</select>

					<span class="help-block">{{{ $errors->first('private', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('groups', ' has-error') }}">

					<label for="groups" class="control-label">{{{ trans('ninjaparade/content::posts/form.groups') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.groups_help') }}}"></i></label>

					<div class="controls">
						<select name="groups[]" id="groups" class="form-control" multiple="true">
						@foreach ($groups as $group)
							<option value="{{{ $group->id }}}"{{ in_array($group->id, $post->groups) ? ' selected="selected"' : null }}>{{{ $group->name }}}</option>
						@endforeach
						</select>
					</div>

					<span class="help-block">{{{ $errors->first('groups', ':message') }}}</span>

				</div>
			</div>
		</div>

		{{-- Attributes tab --}}
		<div class="tab-pane clearfix" id="attributes">

			@widget('platform/attributes::entity.form', [$post])

		</div>

		<div class="tab-pane" id="media">
			<a href="#" class="btn btn-info" data-toggle="modal" data-target="#mediaModal"><i class="fa fa-plus"></i> {{{ trans('button.upload') }}}</a>

			<hr>
			<hr>
			<div class="row" id="uploaded-media-row">
			 <!--  <div class="col-sm-6 col-md-4">
			    <div class="thumbnail">
			      <img data-src="holder.js/300x300" alt="...">
			      <div class="caption">
			        <h3>Thumbnail label</h3>
			        <p>...</p>
			        <p><a href="#" class="btn btn-primary" role="button">Button</a> <a href="#" class="btn btn-default" role="button">Button</a></p>
			      </div>
			    </div>
			  </div> -->
			</div>
		</div>
	</div>

	{{-- Form actions --}}
	<div class="row">

		<div class="col-lg-12 text-right">

			{{-- Form actions --}}
			<div class="form-group">

				<button class="btn btn-success" type="submit">{{{ trans('button.save') }}}</button>

				<a class="btn btn-default" href="{{{ URL::toAdmin('content/posts') }}}">{{{ trans('button.cancel') }}}</a>

				<a class="btn btn-danger" data-toggle="modal" data-target="modal-confirm" href="{{ URL::toAdmin("content/posts/{$post->id}/delete") }}">{{{ trans('button.delete') }}}"</a>

			</div>

		</div>

	</div>

</form>


@include('ninjaparade/content::partials/dropzone')

@stop
