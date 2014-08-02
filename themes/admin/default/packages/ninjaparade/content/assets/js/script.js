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



	$('#dp-tags').selectize({
		maxItems: 4,
		create: true
	});

});
