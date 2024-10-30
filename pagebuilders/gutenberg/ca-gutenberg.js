jQuery(function(){
	(function( blocks, element ) {
		var el 					= element.createElement,
			InspectorControls  	= ('blockEditor' in wp) ? wp.blockEditor.InspectorControls : wp.editor.InspectorControls;

		/* Plugin Category */
		blocks.getCategories().push({slug: 'cpca', title: 'Corner Ad'});

		/* ICONS */
		const iconCPCA = el('img', { width: 20, height: 20, src:  "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAKVJREFUOI1jZHP9/5+BTBDrysDARInmOaUMjGQZANPMwECGC5A1k2wAumaSDFDQRdWccIDhP9EG/JNnYLiljtCUcIDh/8JbEDkWYjT/MYWwF95iYDjyguH/3U9EegFZMwwga8ZrADbN2ABWAxR0idOM1YBYVwaGm70MjPFqZBiAHM8LHBgYlflIMAA9kSQcQA1tvAZg0wyLZ4Iguev//////zOQiwFKyGVe74RH8gAAAABJRU5ErkJggg==" } );

		/* Corner Ad Shortcode */
		blocks.registerBlockType( 'cpca/corner-ad', {
			title: 'Corner Ad',
			icon: iconCPCA,
			category: 'cpca',
			supports: {
				customClassName: false,
				className: false
			},
			attributes: {
				id : {
					type : 'int'
				}
			},

			edit: function( props ) {
				var focus 	  = props.isSelected,
					options   = [],
					id   	  = props.attributes.id;

				(function(){
					if(typeof corner_ad != 'undefined')
					{
						jQuery('<select>'+corner_ad.list+'</select>')
						.find('option')
						.each(
							function()
							{
								var e = jQuery(this),
									v = e.val(),
									t = e.text(),
									o = {key:v, value:v};

								if(typeof id == 'undefined'){
									id = v;
									props.setAttributes({id: v});
								}
								options.push(el('option',o, t));
							}
						);
					}
				})();

				return [
						!!focus &&
						el(
							InspectorControls,
							{key: 'ad-inspector'},
                            el(
                                'div',
                                {
                                    key: 'cp_inspector_container',
                                    style:{paddingLeft:'20px',paddingRight:'20px'}
                                },
                                [
                                    el('p', {key : 'ad-label'}, 'Select the Ad'),
                                    el('select',
                                        {
                                            key: 'ad-list',
                                            onChange: function(evt){
                                                props.setAttributes({id: evt.target.value});
                                                evt.preventDefault();
                                            },
                                            value : id || ''
                                        },
                                        options
                                    ),
                                    el('p', {key: 'container-label', style:{fontStyle: 'italic', color: '#DADADA'}}, 'Display the Ad relative to (leave in blank to display the Ad relative to the webpage) - Available in the professional version of the plugin'),
                                    el('input',
                                        {
                                            key: 'container-field',
                                            style:{width:'100%'},
                                            disabled: true,
                                            type: 'text',
                                            value:'body'
                                        }
                                    )
                                ]
                            )
						),
						el(
							'input',
							{
								key		: 'ads-shortcode',
								type	: 'text',
								style	: { width: '100%'},
								value	: (new wp.shortcode(
											{
												tag		:'corner-ad',
												attrs 	: {
													id : id || ''
												},
												type 	: 'single'
											})).string(),
								onChange : function(evt){
											var sc = wp.shortcode.next('corner-ad', evt.target.value);
											if(sc)
											{
												var id = sc.shortcode.attrs.named[ 'id' ] || '';
												props.setAttributes({
													id : id
												});
											}
										}
							}
						)
					];
			},

			save: function( props ) {
				var attrs = {'id':''};
				if(typeof props.attributes.id != 'undefined') attrs['id'] = props.attributes.id;
				return (new wp.shortcode(
						{
							tag		:'corner-ad',
							attrs 	: attrs,
							type 	: 'single'
						})).string();
			}
		});
	} )(
		window.wp.blocks,
		window.wp.element
	);
});