<div class="modal fade" id="mediaModal" tabindex="-1" role="dialog" aria-labelledby="mediaModalLabel" aria-hidden="true">

	<div class="modal-dialog">

		<div class="modal-content" style="width: 660px;">

			<div id="dropzone" style="height: 360px;overflow-y:scroll;">
				<form action="{{ URL::toAdmin('media/upload') }}" class="media-dropzone dz-clickable" id="mediaUploader">

					{{-- CSRF Token --}}
					<input type="hidden" name="_token" value="{{ csrf_token() }}">

					<select placeholder="{{{ trans('platform/media::form.tags_help') }}}" id="dp-tags" name="tags[]" multiple="multiple" tabindex="-1">
						
					</select>

					<div class="dz-default dz-message"></div>

				</form>
			</div>

			<div class="modal-footer" style="margin-top: 0;">

				<span class="pull-left text-left">
					<div data-media-total-files></div>
					<div data-media-total-size></div>
				</span>

				<button type="button" class="btn btn-success" data-media-upload><i class="fa fa-upload"></i> Start Upload</button>

				<button type="button" class="btn btn-default" data-dismiss="modal" aria-hidden="true">Close</button>

			</div>

		</div>

	</div>

</div>
