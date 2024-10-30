jQuery(function(){
	(function($){
        window ['open_insertion_corner_ad_window'] = function(){

        	var cont = '<div title="Insert Corner Ad" style="text-align:left;padding:10px;">'
					  +'<div style="padding-top:20px;"><div style="clear:both;">Select the AD to insert</div>'
					  +'<div><select aria-label="Ad" id="corner_ad_selector" style="width:100%;display:block !important;visibility:visible !important;">'+corner_ad.list+'</select></div>'
					  +'<div style="color:#CDCDCD;">Display the Ad relative to (leave in blank to display the Ad relative to the webpage)</div>'
					  +'<div><input aria-label="Relative to" type="text" value="" disabled style="width: 100%;" /></div>'
					  +'<div style="color:#CDCDCD;"><em>Only available in the Professional version</em></div>'
					  +'</div>'
					  +'</div>';

			$(cont).dialog({
				dialogClass: 'wp-dialog',
				modal: true,
				closeOnEscape: true,
                close: function(){
                    $(this).remove();
                },
				buttons: [
					{text: 'OK', click: function() {
						var ca  = '[corner-ad id="'+($('#corner_ad_selector').val() || '')+'"]';
						if(send_to_editor) send_to_editor(ca);
						$(this).dialog("close");
					}}
				]
			});
		};
	})(jQuery)
})