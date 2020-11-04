(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$( document ).ready(function() {
		attachmentRemoval();
	});

	function attachmentRemoval(){
		$('.resource.uploaded-attachment').on('click', function(e){
			e.preventDefault(); 
			var attachName = $(this).data('url');
			var pageID = $(this).data('post-id');
			var securityNonce = $('#resource_cpt_nonce').val();
			$.ajax({
				type : "POST",
				dataType : "json",
				url : localizeAjax.ajaxurl,
				data : {action : "removal_res_attachment", attachName : attachName, securityNonce : securityNonce, pageID : pageID},
				success : function(response){
					console.log(response);
					if(response == true){
						location.reload();
					}else{
						alert("Try again later");
					}
				}
			})
		});
	}

})( jQuery );
