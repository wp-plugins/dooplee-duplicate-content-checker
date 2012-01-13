
 //function to submit main form
 	jQuery(function() 
	{
 	    jQuery('#submitform').click(function() 
		{
 	        jQuery('#report_submit').submit();
			
 	    });
 	});


 jQuery(function() 
{


 //Ajax Contact Form Submission
	jQuery("#report_submit").submit(function() 
	{
		alert('test');
		
		var str = jQuery("#report_submit").serialize();

		jQuery.ajax(
		{		
			type: "POST",
			url: "<?php echo plugins_url('/dooplee/contact.php') ?>",
			data: str,

			success: function(msg) {
				result = "";
				if (msg == 'OK') // Message Sent? Show the 'Thank You' message and hide the form
				{
					result = '<div class="notification_error">Your message has been sent. We will return an evaluation to you within 48 hours</div>';
					jQuery('#notification').html(result);
					jQuery("#notification").fadeIn("slow");
				}
				else 
				{
					result = msg;
					jQuery('#notification').html(result);
					jQuery("#notification").fadeIn("slow");
				}

			}
		});
		return false;
	});
});
