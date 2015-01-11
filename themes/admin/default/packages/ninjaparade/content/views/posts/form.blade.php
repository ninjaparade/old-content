@extends('layouts/default')

{{-- Page title --}}
@section('title')
	@parent
	: {{{ trans("ninjaparade/content::posts/general.{$mode}") }}} {{{ $post->exists ? '- ' . $post->name : null }}}
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
								<a class="tip" href="{{ route('admin.ninjaparade.content.posts.all') }}" data-toggle="tooltip" data-original-title="{{{ trans('action.cancel') }}}">
									<i class="fa fa-reply"></i>  <span class="visible-xs-inline">{{{ trans('action.cancel') }}}</span>
								</a>
							</li>
						</ul>

						<span class="navbar-brand">{{{ trans("action.{$mode}") }}} <small>{{{ $post->exists ? $post->id : null }}}</small></span>
					</div>

					{{-- Form: Actions --}}
					<div class="collapse navbar-collapse" id="actions">

						<ul class="nav navbar-nav navbar-right">

							@if ($post->exists)
							<li>
								<a href="{{ route('admin.ninjaparade.content.posts.delete', $post->id) }}" class="tip" data-action-delete data-toggle="tooltip" data-original-title="{{{ trans('action.delete') }}}" type="delete">
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

								<div class="form-group{{ $errors->first('author_id', ' has-error') }}">

									<label for="author_id" class="control-label">{{{ trans('ninjaparade/content::posts/form.author_id') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.author_id_help') }}}"></i></label>

									<input type="text" class="form-control" name="author_id" id="author_id" placeholder="{{{ trans('ninjaparade/content::posts/form.author_id') }}}" value="{{{ Input::old('author_id', $post->author_id) }}}">

									<span class="help-block">{{{ $errors->first('author_id', ':message') }}}</span>

								</div>

				<div class="form-group{{ $errors->first('post_type', ' has-error') }}">

									<label for="post_type" class="control-label">{{{ trans('ninjaparade/content::posts/form.post_type') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.post_type_help') }}}"></i></label>

									<input type="text" class="form-control" name="post_type" id="post_type" placeholder="{{{ trans('ninjaparade/content::posts/form.post_type') }}}" value="{{{ Input::old('post_type', $post->post_type) }}}">

									<span class="help-block">{{{ $errors->first('post_type', ':message') }}}</span>

								</div>

				<div class="form-group{{ $errors->first('slug', ' has-error') }}">

									<label for="slug" class="control-label">{{{ trans('ninjaparade/content::posts/form.slug') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.slug_help') }}}"></i></label>

									<input type="text" class="form-control" name="slug" id="slug" placeholder="{{{ trans('ninjaparade/content::posts/form.slug') }}}" value="{{{ Input::old('slug', $post->slug) }}}">

									<span class="help-block">{{{ $errors->first('slug', ':message') }}}</span>

								</div>

				<div class="form-group{{ $errors->first('excerpt', ' has-error') }}">

									<label for="excerpt" class="control-label">{{{ trans('ninjaparade/content::posts/form.excerpt') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.excerpt_help') }}}"></i></label>

									<input type="text" class="form-control" name="excerpt" id="excerpt" placeholder="{{{ trans('ninjaparade/content::posts/form.excerpt') }}}" value="{{{ Input::old('excerpt', $post->excerpt) }}}">

									<span class="help-block">{{{ $errors->first('excerpt', ':message') }}}</span>

								</div>

				<div class="form-group{{ $errors->first('title', ' has-error') }}">

									<label for="title" class="control-label">{{{ trans('ninjaparade/content::posts/form.title') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.title_help') }}}"></i></label>

									<input type="text" class="form-control" name="title" id="title" placeholder="{{{ trans('ninjaparade/content::posts/form.title') }}}" value="{{{ Input::old('title', $post->title) }}}">

									<span class="help-block">{{{ $errors->first('title', ':message') }}}</span>

								</div>

				<div class="form-group{{ $errors->first('body', ' has-error') }}">

									<label for="body" class="control-label">{{{ trans('ninjaparade/content::posts/form.body') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.body_help') }}}"></i></label>

									<textarea class="form-control" name="body" id="body" placeholder="{{{ trans('ninjaparade/content::posts/form.body') }}}">{{{ Input::old('body', $post->body) }}}</textarea>

									<span class="help-block">{{{ $errors->first('body', ':message') }}}</span>

								</div>

				<div class="form-group{{ $errors->first('cover_image', ' has-error') }}">

									<label for="cover_image" class="control-label">{{{ trans('ninjaparade/content::posts/form.cover_image') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.cover_image_help') }}}"></i></label>

									<input type="text" class="form-control" name="cover_image" id="cover_image" placeholder="{{{ trans('ninjaparade/content::posts/form.cover_image') }}}" value="{{{ Input::old('cover_image', $post->cover_image) }}}">

									<span class="help-block">{{{ $errors->first('cover_image', ':message') }}}</span>

								</div>

				<div class="form-group{{ $errors->first('images', ' has-error') }}">

									<label for="images" class="control-label">{{{ trans('ninjaparade/content::posts/form.images') }}} <i class="fa fa-info-circle" data-toggle="popover" data-content="{{{ trans('ninjaparade/content::posts/form.images_help') }}}"></i></label>

									<input type="text" class="form-control" name="images" id="images" placeholder="{{{ trans('ninjaparade/content::posts/form.images') }}}" value="{{{ Input::old('images', $post->images) }}}">

									<span class="help-block">{{{ $errors->first('images', ':message') }}}</span>

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

						</fieldset>

					</div>

					{{-- Form: Attributes --}}
					<div role="tabpanel" class="tab-pane fade" id="attributes">
						@widget('platform/attributes::entity.form', [ $post ])
					</div>

				</div>

			</div>

		</div>

	</form>

</section>
@stop
