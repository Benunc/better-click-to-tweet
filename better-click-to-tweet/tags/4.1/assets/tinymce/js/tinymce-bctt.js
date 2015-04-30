( function() {
	tinymce.PluginManager.add( 'bctt', function( editor, url ) {

		// Add a button that opens a window
		editor.addButton( 'bctt', {

			text: '',
			tooltip: 'Better Click To Tweet Shortcode Generator',
			icon: 'bctt-tweet',
			onclick: function() {
				// Open window
				editor.windowManager.open( {
					title: 'Better Click To Tweet Shortcode Generator',
					body: [
						{
							type: 'textbox',
							name: 'tweet',
							label: 'Tweetable Quote',
							multiline : true,
							minHeight : 60
						},
						{
							type: 'checkbox',
							checked: true,
							name: 'viamark',
							value: true,
							text: 'Add \"via @YourTwitterName\" to this tweet',
							label: 'Include "via"?',
						}
					],
					width: 500,
					height: 120,
					onsubmit: function( e ) {

						// bail without tweet text
						if ( e.data.tweet === '' ) {
							return;
						}

						// build my content
						var bcttBuild   = '';

						// set initial
						bcttBuild  += '[bctt tweet="' + e.data.tweet + '"';

						// check for via
						if ( e.data.viamark === false ) {
							bcttBuild  += ' via="no"';
						}

						// close it up
						bcttBuild  += ']';

						// Insert content when the window form is submitted
						editor.insertContent( bcttBuild );
					}
				});
			}
		});
	});
})();