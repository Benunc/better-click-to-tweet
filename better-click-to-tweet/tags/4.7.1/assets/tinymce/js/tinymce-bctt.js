( function() {
	tinymce.PluginManager.add( 'bctt', function( editor, url ) {

		// Add a button that opens a window
		editor.addButton( 'bctt', {

			text: '',
			tooltip: editor.getLang( 'bctt.toolTip', 'Better Click To Tweet Shortcode Generator' ),
			icon: 'bctt-tweet',
			onclick: function() {
				// Open window
				editor.windowManager.open( {
					title: editor.getLang( 'bctt.windowTitle', 'Better Click To Tweet Shortcode Generator' ),
					body: [
						{
							type: 'textbox',
							name: 'tweet',
							label: editor.getLang( 'bctt.tweetableQuote', 'Tweetable Quote' ),
							multiline : true,
							minHeight : 60
						},
						{
							type: 'checkbox',
							checked: true,
							name: 'viamark',
							value: true,
							text: editor.getLang( 'bctt.viaExplainer', 'Add the username below to this tweet'),
							label: editor.getLang( 'bctt.viaPrompt', 'Include "via"?'),
						},
						{
							type: 'textbox',
							name: 'username',
							label: editor.getLang( 'bctt.usernameExplainer', 'Which Twitter username?'),
							multiline: false,
							value: editor.getLang( 'bctt.userPrePopulated', ''),
						}
					],
					width: 800,
					height: 180,
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
						
						} else {
							bcttBuild += ' username="' + e.data.username + '"';
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