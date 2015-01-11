@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@parent
	: {{{ trans("ninjaparade/content::authors/general.{$mode}") }}} {{{ $author->exists ? '- ' . $author->name : null }}}
@stop

{{-- Inline scripts --}}
@section('scripts')
@parent
@stop

{{-- Inline styles --}}
@section('styles')
@parent
@stop

{{-- Page --}}
@section('page')

<section class="panel panel-default panel-tabs">

	{{-- Form --}}
	<form id="content-form" action="{{ request()->fullUrl() }}" role="form" method="post">

		{{-- Form: CSRF Token --}}
		<input type="hidden" name="_token" value="{{ csrf_token() }}">

		<header class="panel-heading">

			<nav class="navbar navbar-default navbar-actions">

				<div class="container-fluid">

					<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#actions">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						</button>

						<ul class="nav navbar-nav navbar-cancel">
							<li>
								<a class="tip" href="{{ route('admin.ninjaparade.content.authors.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
									<i class="fa fa-reply"></i>  <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
								</a>
							</li>
						</ul>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $author->exists ? $author->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($author->exists)
							<li>
								<a href="{{ route('admin.ninjaparade.content.authors.delete', $author->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
									<i class="fa fa-trash-o"></i>  <span class="visible-xs-inline">{{{ trans('action.delete') }}}</span>
								</a>
							</li>
							@endif

							<li>
								<button class="btn btn-primary navbar-btn" data-toggle="tooltip" data-original-title="{{{ trans('action.save') }}}">
									<i class="fa fa-save"></i>  <span class="visible-xs-inline">{{{ trans('action.save') }}}</span>
								</button>
							</li>

						</ul>

					</div>

				</div>

			</nav>

		</header>

		<div class="panel-body">

			<div role="tabpanel">

				{{-- Form: Tabs --}}
				<ul class="nav nav-tabs" role="tablist">
					<li class="active" role="presentation"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">{{{ trans('common.tabs.general') }}}</a></li>
					<li role="presentation"><a href="#attributes" aria-controls="attributes" role="tab" data-toggle="tab">{{{ trans('common.tabs.attributes') }}}</a></li>
				</ul>

				<div class="tab-content">

					{{-- Form: General --}}
					<div role="tabpanel" class="tab-pane fade in active" id="general">

						<fieldset>

							<div class="row">

								<div class="form-group{{ $errors->first('name', ' has-error') }}">

									<label for="name" class="control-label">{{{ trans('ninjaparade/content::authors/form.name') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::authors/form.name_help') }}}"></i></label>

									<textarea class="form-control" name="name" id="name" placeholder="{{{ trans('ninjaparade/content::authors/form.name') }}}">{{{ Input::old('name', $author->name) }}}</textarea>

									<span class="help-block">{{{ $errors->first('name', ':message') }}}</span>

								</div>

				<div class="form-group{{ $errors->first('postion', ' has-error') }}">

									<label for="postion" class="control-label">{{{ trans('ninjaparade/content::authors/form.postion') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::authors/form.postion_help') }}}"></i></label>

									<textarea class="form-control" name="postion" id="postion" placeholder="{{{ trans('ninjaparade/content::authors/form.postion') }}}">{{{ Input::old('postion', $author->postion) }}}</textarea>

									<span class="help-block">{{{ $errors->first('postion', ':message') }}}</span>

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

						</fieldset>

					</div>

					{{-- Form: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@widget('platform/attributes::entity.form', [ $author ])
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
