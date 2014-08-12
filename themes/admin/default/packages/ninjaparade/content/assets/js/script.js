$(function() {
	
	if( $('.redactor').length )
	{
		$('.redactor').redactor({ 
			minHeight: 300,
			tidyHtml: true
		});	
	}
	
	if( $('#slug').length )
	{
		$(document).on('keyup', '#title', function()
		{
			$('#slug').val($(this).val().slugify());
			
		});
	}
	

	if( $('#tags').length )
	{
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
	}
	

	if( $('#dp-tags').length )
	{
		$('#dp-tags').selectize({
			maxItems: 4,
			create: true
		});
	}
	

});
