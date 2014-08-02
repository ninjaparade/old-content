@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@parent
	: {{{ trans("ninjaparade/content::authors/general.{$mode}") }}} {{{ $author->exists ? '- ' . $author->name : null }}}
@stop

{{-- Queue assets --}}
{{ Asset::queue('bootstrap.tabs', 'bootstrap/js/tab.js', 'jquery') }}
{{ Asset::queue('content', 'ninjaparade/content::js/script.js', 'jquery') }}

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

@section('breadcrumb')
@parent
<li><a href="{{URL::route('posts.index')}}">All Authors</a></li>
<li>{{Str::title($mode)}} Author</li>
@show
{{-- Page content --}}
@section('content')

{{-- Page header --}}
<div class="page-header">

	<h1>{{{ trans("ninjaparade/content::authors/general.{$mode}") }}} <small>{{{ $author->name }}}</small></h1>

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

				<div class="form-group{{ $errors->first('name', ' has-error') }}">

					<label for="name" class="control-label">{{{ trans('ninjaparade/content::authors/form.name') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::authors/form.name_help') }}}"></i></label>

					<textarea class="form-control" name="name" id="name" placeholder="{{{ trans('ninjaparade/content::authors/form.name') }}}">{{{ Input::old('name', $author->name) }}}</textarea>

					<span class="help-block">{{{ $errors->first('name', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('position', ' has-error') }}">

					<label for="position" class="control-label">{{{ trans('ninjaparade/content::authors/form.position') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::authors/form.position_help') }}}"></i></label>

					<textarea class="form-control" name="position" id="position" placeholder="{{{ trans('ninjaparade/content::authors/form.position') }}}">{{{ Input::old('position', $author->position) }}}</textarea>

					<span class="help-block">{{{ $errors->first('position', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('bio', ' has-error') }}">

					<label for="bio" class="control-label">{{{ trans('ninjaparade/content::authors/form.bio') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::authors/form.bio_help') }}}"></i></label>

					<textarea class="form-control" name="bio" id="bio" placeholder="{{{ trans('ninjaparade/content::authors/form.bio') }}}">{{{ Input::old('bio', $author->bio) }}}</textarea>

					<span class="help-block">{{{ $errors->first('bio', ':message') }}}</span>

				</div>

				<div class="form-group{{ $errors->first('avatar', ' has-error') }}">

					<label for="avatar" class="control-label">{{{ trans('ninjaparade/content::authors/form.avatar') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::authors/form.avatar_help') }}}"></i></label>

					<input type="text" class="form-control" name="avatar" id="avatar" placeholder="{{{ trans('ninjaparade/content::authors/form.avatar') }}}" value="{{{ Input::old('avatar', $author->avatar) }}}">

					<span class="help-block">{{{ $errors->first('avatar', ':message') }}}</span>

				</div>


			</div>

		</div>

		{{-- Attributes tab --}}
		<div class="tab-pane clearfix" id="attributes">

			@widget('platform/attributes::entity.form', [$author])

		</div>

	</div>

	{{-- Form actions --}}
	<div class="row">

		<div class="col-lg-12 text-right">

			{{-- Form actions --}}
			<div class="form-group">

				<button class="btn btn-success" type="submit">{{{ trans('button.save') }}}</button>

				<a class="btn btn-default" href="{{{ URL::toAdmin('content/authors') }}}">{{{ trans('button.cancel') }}}</a>

				<a class="btn btn-danger" data-toggle="modal" data-target="modal-confirm" href="{{ URL::toAdmin("content/authors/{$author->id}/delete") }}">{{{ trans('button.delete') }}}</a>

			</div>

		</div>

	</div>

</form>

@stop
