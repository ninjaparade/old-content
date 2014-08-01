@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@parent
	: {{{ trans("ninjaparade/content::posttypes/general.{$mode}") }}} {{{ $posttype->exists ? '- ' . $posttype->name : null }}}
@stop

{{-- Queue assets --}}
{{ Asset::queue('bootstrap.tabs', 'bootstrap/js/tab.js', 'jquery') }}
{{ Asset::queue('content', 'ninjaparade/content::js/script.js', 'jquery') }}

{{ Asset::queue('platform.slugify.js', 'platform/js/slugify.js', 'jquery') }}
{{-- Inline scripts --}}
@section('scripts')
<script>
	$(function() {
	
		$(document).on('keyup', '#title', function()
		{
			$('#slug').val($(this).val().slugify());
			
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

	<h1>{{{ trans("ninjaparade/content::posttypes/general.{$mode}") }}} <small>{{{ $posttype->name }}}</small></h1>

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

					<label for="title" class="control-label">{{{ trans('ninjaparade/content::posttypes/form.title') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posttypes/form.title_help') }}}"></i></label>

					<input type="text" class="form-control" name="title" id="title" placeholder="{{{ trans('ninjaparade/content::posttypes/form.title') }}}" value="{{{ Input::old('title', $posttype->title) }}}">

					<span class="help-block">{{{ $errors->first('title', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('slug', ' has-error') }}">

					<label for="slug" class="control-label">{{{ trans('ninjaparade/content::posttypes/form.slug') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posttypes/form.slug_help') }}}"></i></label>

					<input type="text" class="form-control" name="slug" id="slug" placeholder="{{{ trans('ninjaparade/content::posttypes/form.slug') }}}" value="{{{ Input::old('slug', $posttype->slug) }}}">

					<span class="help-block">{{{ $errors->first('slug', ':message') }}}</span>

				</div>

			</div>

		</div>

		{{-- Attributes tab --}}
		<div class="tab-pane clearfix" id="attributes">

			@widget('platform/attributes::entity.form', [$posttype])

		</div>

	</div>

	{{-- Form actions --}}
	<div class="row">

		<div class="col-lg-12 text-right">

			{{-- Form actions --}}
			<div class="form-group">

				<button class="btn btn-success" type="submit">{{{ trans('button.save') }}}</button>

				<a class="btn btn-default" href="{{{ URL::toAdmin('content/posttypes') }}}">{{{ trans('button.cancel') }}}</a>

				<a class="btn btn-danger" data-toggle="modal" data-target="modal-confirm" href="{{ URL::toAdmin("content/posttypes/{$posttype->id}/delete") }}">{{{ trans('button.delete') }}}</a>

			</div>

		</div>

	</div>

</form>

@stop
