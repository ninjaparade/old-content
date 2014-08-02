$(function() {
	
	$('.redactor').redactor({ 
		minHeight: 300,
		tidyHtml: true,

	});

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

	$.mediamanager('#mediaUploader', {
		onSuccess : function(response)
		{
			console.log(response);
		}
	});

	$('#dp-tags').selectize({
		maxItems: 4,
		create: true
	});

});
