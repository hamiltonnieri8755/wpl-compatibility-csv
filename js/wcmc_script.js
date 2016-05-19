jQuery(document).ready(function () {

	jQuery("#post").attr("enctype", "multipart/form-data");
	
	// Reset Handler
	jQuery("#reset_compatibility_csv").click( function () {
		jQuery("#upload_compatibility").prop("disabled", false);
		jQuery("input[name='compatibility_csv_flag']").val("0");
		jQuery(".reset-hbtn").click();
	});	

	// Add Compatibility CSV Handler
	jQuery("#upload_compatibility").click( function () {
		if ( jQuery("input[name='compatibility_csv'").val() ) {
			jQuery(this).prop("disabled", true);
			jQuery("input[name='compatibility_csv_flag']").val("1");
		}
	});

	// CSV File Select Change Handler
	jQuery("input[name='compatibility_csv'").change( function () {
		jQuery("#upload_compatibility").prop("disabled", false);
		jQuery("input[name='compatibility_csv_flag']").val("0");
	});

});