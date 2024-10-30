jQuery(document).ready( function() {
	// Put your JS in here, and it will run after the DOM has loaded.
	
	// jQuery.post( ajaxurl, {
	// 	action: 'my_example_action',
	// 	'cookie': encodeURIComponent(document.cookie),
	// 	'parameter_1': 'some_value'
	// }, 
	// function(response) { 
	// 	... 
	// } );

	    jQuery('#mycarousel').jcarousel({
        // Configuration goes here
		'visible' : 600,
		'scroll': 5
    	});
		
		
	    jQuery('img.giftitem').click(function() {
		var imagepath = jQuery(this).attr("src");
		var imageid = jQuery(this).attr("id");
		var imagename = jQuery(this).attr("name");
		var previousegift = jQuery('img.giftbox').attr("id");
		var selectedgift = 'img.giftitem#' + previousegift;
		if (previousegift == '999'){selectedgift = 'img.giftbox';}
		jQuery(this).attr("src",jQuery(selectedgift).attr("src"));
		jQuery(selectedgift).attr("src",jQuery('img.giftbox').attr("src"));
  		//alert(jQuery.get(url));
		//jQuery('div.testajax').attr("name","hahaha");
		//jQuery('img.giftitem').attr("border","0");
		
		//jQuery(this).animate({opacity: "0.5"});
		
		jQuery('img.giftbox').attr("src",imagepath);
		jQuery('img.giftbox').attr("id",imageid);
		jQuery('img.giftbox').attr("name",imagename);
		//jQuery('img#gifts').attr("src",aaa);
		//jQuery('textarea').val(aaa);
		//jQuery('input#gift-button-submit').hidden;
	    });
	    
		
	    jQuery('span#sendgift-button').click(function() {
  		//echo 'Handler for span called.' ;
	    //});
		
		//-------------------------------------------------------//
		
		//jQuery('span#sendgift-button').livequery('click',
		//function() { 
			//alert('ok');
			//jQuery('#ajax-loader-members').toggle();

			//jQuery("div#members-list-options a").removeClass("selected");
			//jQuery(this).addClass('selected');
			//jQuery('.carousel').attr("style",'display:none;');
			//jQuery('.sendgift-panel').attr("style",'display:none;');
			jQuery('div#bpgifts-alert').html('');
			if (jQuery('img.giftbox').attr("name") != 'emptybox') {
			
			jQuery('.sendgift-panel').hide();
		    jQuery('div#bpgifts-waiting').show();
			
			jQuery.post( ajaxurl, {
				action: 'bpgifts_sendgift_updatedb',
				//'cookie': encodeURIComponent(document.cookie),
				//'_wpnonce': jQuery("input#_wpnonce-members").val(),
				'gift_id': jQuery('img.giftbox').attr("id"),
				'gift_name': jQuery('img.giftbox').attr("name"),
				'gift_path': jQuery('img.giftbox').attr("src"),
				'gift_message': jQuery('textarea#giftms').val()
				},
				function(response)
					{	
					//jQuery('#ajax-loader-members').toggle();
					//member_wiget_response(response);
					jQuery('div#bpgifts-waiting').hide();
					jQuery('div#bpgifts-alert').html('<span class="highlight">'+response+'</span>');
					//jQuery('span#bpgifts-alert').fadeIn(200);
					}
			);
			
			} else {jQuery('div#bpgifts-alert').html('<span class="highlight">'+'Please select gift'+'</span>');}
			
			return false;
		}
		);

		//-------------------------------------------------------//
	    
});
