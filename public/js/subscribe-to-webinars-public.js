	jQuery(document).ready(function(e){
			var is_form = '';
			var webinar_title = '';
			var div_content = '<div class="webinar-container"><h2 style="padding-bottom:20px;">Subscribe to this Webinar</h2><input id="pagename" name="pagename" value="'+subscribe_to_webinar_vars.pagename+'" type="hidden" /><input id="pageurl" name="pageurl" value="'+subscribe_to_webinar_vars.pageurl+'" type="hidden" /><input id="hubutk" name="hubutk" value="'+subscribe_to_webinar_vars.hubutk+'" type="hidden" /><input id="ipaddr" name="ipaddr" value="'+subscribe_to_webinar_vars.ipaddr+'" type="hidden" /><input id="webinar_title" name="webinar_title" value="" type="hidden" /><input class="webinar_notify_popup_tag_fields" id="email" type="text" name="email" placeholder="Business Email*" required="" aria-required="true" value="" /><p class="sbmt_btn"><button class="et_pb_contact_submit et_pb_button webinar_notify_popup_popup_form_submit" type="submit" name="webinar_notify_popup_popup_form_submit">SUBSCRIBE</button></p><p class="message"></p></div>';
			jQuery('.webinar_notify_trigger').click(function(){
				is_form = jQuery('.webinar-container');
				if(is_form.length == 0){
					jQuery('#webinar_notify_form').html(div_content);
				}
				webinar_title = jQuery(this).attr('data-attr-title');
				jQuery('#webinar_title').val(webinar_title);
				jQuery('#webinar_subscribed').html(webinar_title);
				
				jQuery('.webinar_notify').fadeIn();
			});
			jQuery('#webinar-notify-close-button').click(function(){
				jQuery('.webinar_notify').fadeOut();
			});



			jQuery.validator.addMethod("validEmail", function (value, element) {
				var atpos = value.indexOf("@");
				var dotpos = value.lastIndexOf(".");
				if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= value.length) {
					return false;
				} else {
					return true;
				}
			}, "Please enter valid email");




			jQuery('form#webinar_notify_form').validate({
				onkeyup: false,
				rules: {
					"email": {
						required: true,
						validEmail: true,
						remote: {
							url: "https://api.visualbi.com/domain-validation/",
							type: "post",
							data: {
								email: function() {
									return jQuery("input#email").val();
								}
							},
							complete: function(data) {
						
							}
						}
					},

				},
				submitHandler: function () {
					var wt = jQuery("input#webinar_title").val();
					var em = jQuery("input#email").val();
					var pn = jQuery("input#pagename").val();
					var pu = jQuery("input#pageurl").val();
					var hs = jQuery("input#hubutk").val();
					var ip = jQuery("input#ipaddr").val();

					var data = {
						action: 'webinar_notify_form_submission',
						webinar_title: wt,
						email: em,
						pagename: pn,
						pageurl: pu,
						hubspotutk: hs,
						ipaddr: ip
					};

					var block_config = {
						message:'<h3>Just a moment...</h3>',
						css: { 
							padding:        '20px 0px', 
							margin:         0, 
							width:          '80%', 
							top:            '40%', 
							left:           '10%', 
							textAlign:      'center', 
							color:          '#fff', 
							border:         'none', 
							backgroundColor:'rgba(255,255,255,0)', 
							cursor:         'wait' 
						},
						overlayCSS:  { 
							backgroundColor: '#fff', 
							opacity:         0.6, 
							cursor:          'wait' 
						},
					};

					jQuery('form#webinar_notify_form').block(block_config);

					jQuery.post("<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>", data, function(response){
						if(response == 'success'){
							jQuery('form#webinar_notify_form').html('<p style="padding:20px 30px;">Thank you for the submission, you will be notified when the webinar date is announced.</p>');
						}else if(response == 'inemail'){
							jQuery('form#webinar_notify_form p.message').html('Please enter a valid business email.');

						}else{
							jQuery('form#webinar_notify_form p.message').html('Form Submission Failed. Please try Again.');
						}
						jQuery('form#webinar_notify_form').unblock();
					});		
				}
			});


		});


	