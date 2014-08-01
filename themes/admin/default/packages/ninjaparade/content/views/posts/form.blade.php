@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@parent
	: {{{ trans("ninjaparade/content::posts/general.{$mode}") }}} {{{ $post->exists ? '- ' . $post->name : null }}}
@stop

{{-- Queue assets --}}
{{ Asset::queue('bootstrap.tabs', 'bootstrap/js/tab.js', 'jquery') }}
{{ Asset::queue('content', 'ninjaparade/content::js/script.js', 'jquery') }}

{{ Asset::queue('imperavi.redactor.js', 'imperavi/js/redactor.min.js', 'jquery') }}
{{ Asset::queue('imperavi.redactor.css', 'imperavi/css/redactor.css') }}

{{ Asset::queue('platform.slugify.js', 'platform/js/slugify.js', 'jquery') }}

{{ Asset::queue('selectize.js', 'selectize/js/selectize.js', 'jquery') }}

{{ Asset::queue('selectize.css', 'selectize/css/selectize.css', 'styles') }}

{{-- Inline scripts --}}
@section('scripts')
<script>
	$(function() {
		
		$('.redactor').redactor({ minHeight: 300});

		$(document).on('keyup', '#title', function()
		{
			$('#slug').val($(this).val().slugify());
			
		});

		$('#tags').selectize({
		    delimiter: ',',
		    persist: false,
		    create: function(input) {
		        return {
		            value: input,
		            text: input
		        }
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

					<textarea type="text" class="form-control" name="pullquote" id="pullquote" placeholder="{{{ trans('ninjaparade/content::posts/form.pullquote') }}}" value="{{{ Input::old('pullquote', $post->pullquote) }}}"></textarea>

					<span class="help-block">{{{ $errors->first('pullquote', ':message') }}}</span>

				</div>

				

				

				<div class="form-group{{ $errors->first('publish_status', ' has-error') }}">

					<label for="publish_status" class="control-label">{{{ trans('ninjaparade/content::posts/form.publish_status') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.publish_status_help') }}}"></i></label>

					<div class="checkbox">
						<label>
							<input type="hidden" name="publish_status" id="publish_status" value="0" checked>
							<input type="checkbox" name="publish_status" id="publish_status" @if($post->publish_status) }}}) checked @endif value="1"> {{ ucfirst('publish_status') }}
						</label>
					</div>

					<span class="help-block">{{{ $errors->first('publish_status', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('private', ' has-error') }}">

					<label for="private" class="control-label">{{{ trans('ninjaparade/content::posts/form.private') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.private_help') }}}"></i></label>

					<div class="checkbox">
						<label>
							<input type="hidden" name="private" id="private" value="0" checked>
							<input type="checkbox" name="private" id="private" @if($post->private) }}}) checked @endif value="1"> {{ ucfirst('private') }}
						</label>
					</div>

					<span class="help-block">{{{ $errors->first('private', ':message') }}}</span>

				</div>


			</div>

		</div>

		{{-- Attributes tab --}}
		<div class="tab-pane clearfix" id="attributes">

			@widget('platform/attributes::entity.form', [$post])

		</div>

	</div>

	{{-- Form actions --}}
	<div class="row">

		<div class="col-lg-12 text-right">

			{{-- Form actions --}}
			<div class="form-group">

				<button class="btn btn-success" type="submit">{{{ trans('button.save') }}}</button>

				<a class="btn btn-default" href="{{{ URL::toAdmin('content/posts') }}}">{{{ trans('button.cancel') }}}</a>

				<a class="btn btn-danger" data-toggle="modal" data-target="modal-confirm" href="{{ URL::toAdmin("content/posts/{$post->id}/delete") }}">{{{ trans('button.delete') }}}</a>

			</div>

		</div>

	</div>

</form>

@stop
