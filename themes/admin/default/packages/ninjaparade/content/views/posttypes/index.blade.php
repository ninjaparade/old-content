@extends('layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('ninjaparade/content::posttypes/general.title') }} ::
@parent
@stop

{{-- Queue Assets --}}
{{ Asset::queue('underscore', 'underscore/js/underscore.js', 'jquery') }}
{{ Asset::queue('data-grid', 'cartalyst/js/data-grid.js', 'underscore') }}
{{ Asset::queue('moment', 'moment/js/moment.js') }}

{{ Asset::queue('ninjaparade-content', 'ninjaparade/content::css/style.css', 'bootstrap') }}


{{-- Partial Assets --}}
@section('assets')
@parent
@stop

{{-- Inline Styles --}}
@section('styles')
@parent
@stop

{{-- Inline Scripts --}}
@section('scripts')
@parent
<script>
$(function()
{
	var dg = $.datagrid('posttype', '.data-grid', '.data-grid_pagination', '.data-grid_applied', {
		loader: '.loading',
		scroll: '.data-grid',
		callback: function()
		{
			$('#checkAll').prop('checked', false);

			$('#actions').prop('disabled', true);
		}
	});

	$(document).on('click', '#checkAll', function()
	{
		$('input:checkbox').not(this).prop('checked', this.checked);

		var status = $('input[name="entries[]"]:checked').length > 0;

		$('#actions').prop('disabled', ! status);
	});

	$(document).on('click', 'input[name="entries[]"]', function()
	{
		var status = $('input[name="entries[]"]:checked').length > 0;

		$('#actions').prop('disabled', ! status);
	});

	$(document).on('click', '[data-action]', function(e)
	{
		e.preventDefault();

		var action = $(this).data('action');

		var entries = $.map($('input[name="entries[]"]:checked'), function(e, i)
		{
			return +e.value;
		});

		$.ajax({
			type: 'POST',
			url: '{{ URL::toAdmin('content/posttypes') }}',
			data: {
				action : action,
				entries: entries
			},
			success: function(response)
			{
				dg.refresh();
			}
		});
	});
});
</script>
@stop

{{-- Page content --}}
@section('content')

{{-- Page header --}}
<div class="page-header">

	<h1>{{{ trans('ninjaparade/content::posttypes/general.title') }}}</h1>

</div>

<div class="row">

	<div class="col-lg-7">

		{{-- Data Grid : Applied Filters --}}
		<div class="data-grid_applied" data-grid="posttype"></div>

	</div>

	<div class="col-lg-5 text-right">

		<form method="post" action="" accept-charset="utf-8" data-search data-grid="posttype" class="form-inline" role="form">

			<div class="form-group">

				<div class="loading"></div>

			</div>

			<div class="btn-group text-left">

				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
					{{{ trans('general.filters') }}} <span class="caret"></span>
				</button>

				<ul class="dropdown-menu" role="menu" data-grid="posttype">
					<li><a href="#" data-reset>{{{ trans('general.show_all') }}}</a></li>
					<li><a href="#" data-filter="enabled:1" data-label="enabled::{{{ trans('general.all_enabled') }}}" data-reset>{{{ trans('general.show_enabled') }}}</a></li>
					<li><a href="#" data-filter="enabled:0" data-label="enabled::{{{ trans('general.all_disabled') }}}" data-reset>{{{ trans('general.show_disabled') }}}</a></li>
				</ul>

			</div>

			<div class="form-group has-feedback">

				<input name="filter" type="text" placeholder="{{{ trans('general.search') }}}" class="form-control">

				<span class="glyphicon fa fa-search form-control-feedback"></span>

			</div>

			<a class="btn btn-primary" href="{{ URL::toAdmin('content/posttypes/create') }}"><i class="fa fa-plus"></i> {{{ trans('button.create') }}}</a>

		</form>

	</div>

</div>

<br />

<table data-source="{{ URL::toAdmin('content/posttypes/grid') }}" data-grid="posttype" class="data-grid table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th><input type="checkbox" name="checkAll" id="checkAll"></th>
			<th class="sortable" data-sort="id">{{{ trans('ninjaparade/content::posttypes/table.id') }}}</th>
			<th class="sortable" data-sort="slug">{{{ trans('ninjaparade/content::posttypes/table.slug') }}}</th>
			<th class="sortable" data-sort="title">{{{ trans('ninjaparade/content::posttypes/table.title') }}}</th>
			<th class="sortable" data-sort="created_at">{{{ trans('ninjaparade/content::posttypes/table.created_at') }}}</th>
		</tr>
	</thead>
	<tbody></tbody>
</table>

{{-- Data Grid : Pagination --}}
<div class="data-grid_pagination" data-grid="posttype"></div>

@include('ninjaparade/content::grids/posttype/results')
@include('ninjaparade/content::grids/posttype/filters')
@include('ninjaparade/content::grids/posttype/pagination')
@include('ninjaparade/content::grids/posttype/no_results')
@include('ninjaparade/content::grids/posttype/no_filters')

@stop
