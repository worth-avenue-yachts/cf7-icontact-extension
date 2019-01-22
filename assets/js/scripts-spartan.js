jQuery(document).ready(function() {

	try {

		if (! jQuery('#wpcf7-icontact-cf-active').is(':checked'))

			jQuery('.icontact-custom-fields').hide();

		jQuery('#wpcf7-icontact-cf-active').click(function() {

			if (jQuery('.icontact-custom-fields').is(':hidden')
			&& jQuery('#wpcf7-icontact-cf-active').is(':checked')) {

				jQuery('.icontact-custom-fields').slideDown('fast');
			}

			else if (jQuery('.icontact-custom-fields').is(':visible')
			&& jQuery('#wpcf7-icontact-cf-active').not(':checked')) {

				jQuery('.icontact-custom-fields').slideUp('fast');
        jQuery(this).closest('form').find(".icontact-custom-fields input[type=text]").val("");

			}

		});



		jQuery(".vcic-trigger").click(function() {

			jQuery(".vcic-support").slideToggle("fast");

      jQuery(this).text(function(i, text){
          return text === "Show advanced settings" ? "Hide advanced settings" : "Show advanced settings";
      })

			return false; //Prevent the browser jump to the link anchor

		});


    jQuery(".vcic-trigger2").click(function() {
      jQuery(".vcic-support2").slideToggle("fast");
      return false; //Prevent the browser jump to the link anchor
    });


    jQuery(".vcic-trigger3").click(function() {
      jQuery(".vcic-support3").slideToggle("fast");
      return false; //Prevent the browser jump to the link anchor
    });


	}

	catch (e) {

	}

});