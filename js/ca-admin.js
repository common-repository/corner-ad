jQuery(function(){
	(function($){
        // Main application
		jQuery(document).on('change', '[name="corner_ad_context"]', function(){
			if(jQuery('[name="corner_ad_context"]:checked').val() == 'posttype')
			{
				jQuery('.corner-ad-post-types-list').show();
			}
			else
			{
				jQuery('.corner-ad-post-types-list').hide();
			}
		});

		jQuery(document).on('submit','[name="corner_ad_default_ad_settings"]', function(evt){

			if(
				jQuery('[name="corner_ad_context"]:checked').val() == 'posttype' &&
				!jQuery('[name*="corner_ad_posttype_list"]').val()
			)
			{
				if(
					'corner_ad_default_ad_errors' in window &&
					'posttype_required' in corner_ad_default_ad_errors
				)
				alert(corner_ad_default_ad_errors['posttype_required']);
				return false;
			}

			return true;
		});

		jQuery('.corner_ad_button_for_upload').on('click', function(){
			var corner_ad_img_path_field = jQuery(this).prev('input[type="text"]');
			var media = wp.media({
					title: 'Select Media File',
					library:{
						type: 'image'
					},
					button: {
					text: 'Select Item'
					},
					multiple: false
			}).on('select',
				(function( field ){
					return function() {
						var attachment = media.state().get('selection').first().toJSON();
						var url = attachment.url;
						field.val( url );
					};
				})( corner_ad_img_path_field )
			).open();
			return false;
		});

        jQuery('.corner_ad_thumb_chk').on('click', function(){
			var e 		 = jQuery( this ),
				thumbBtn = e.prev( 'input' ),
				thumbBox = thumbBtn.prev( 'input' ),
				action 	 = 'show';

			if( e.is(':checked') )
			{
				action = 'hide';
				thumbBox.val('');
			}


			thumbBox[ action ]();
			thumbBtn[ action ]();
		});

        jQuery('.corner_ad_button_for_add_img_field').on('click', function(){
            alert('Only one image may be associated to the Ad in the free version of plugin');
			return false;
		});

		// From and to date fields.

		var corner_ad_from = $('[name="corner_ad_from"]'),
			corner_ad_to = $('[name="corner_ad_to"]'),
			dp_args = {
				changeYear: true,
				dateFormat: 'yy-mm-dd',
				yearRange: 'c:c+5'
			};
		if(corner_ad_from.length) 	$('[name="corner_ad_from"]').datepicker(dp_args);
		if(corner_ad_to.length) 	$('[name="corner_ad_to"]').datepicker(dp_args);
    })(jQuery)
})