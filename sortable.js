
(function($){
	$(document).ready(function(){
		$( "#sortable" ).sortable({
			items: "li:not(.ui-state-disabled)"
		});

		$( "#sortable li" ).disableSelection();
	});
})(jQuery);