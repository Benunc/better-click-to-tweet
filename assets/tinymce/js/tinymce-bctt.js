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
							id: 'bctt-tweet-textbox',
							label: editor.getLang( 'bctt.tweetableQuote', 'Tweetable Quote' ),
							multiline : true,
							minHeight : 60,
							onkeyup: function() { 
								var tweetLength = jQuery( '#bctt-tweet-textbox' ).val().length;
								tweetLength += 24; // Character count of URL plus space
								if ( 'true' == jQuery( '#bctt-viamark-checkbox' ).attr( 'aria-checked' ) ) {
									tweetLength += jQuery( '#bctt-username-textbox' ).val().length + 6; // Username count plus space & 'via'
								}
								jQuery( '#bctt-text-length p span' ).html( tweetLength );
							}
						},
						{
							type: 'checkbox',
							checked: true,
							name: 'viamark',
							id: 'bctt-viamark-checkbox',
							value: true,
							text: editor.getLang( 'bctt.viaExplainer', 'Add the username below to this tweet' ),
							label: editor.getLang( 'bctt.viaPrompt', 'Include "via"?'),
							onclick: function() { 
								var tweetLength = jQuery( '#bctt-tweet-textbox' ).val().length;
								tweetLength += 24; // Character count of URL
								if ( 'true' == jQuery( '#bctt-viamark-checkbox' ).attr( 'aria-checked' ) ) {
									tweetLength += jQuery( '#bctt-username-textbox' ).val().length + 6; // Username count plus space & 'via'
								}
								jQuery( '#bctt-text-length p span' ).html( tweetLength );
							}
						},
						{
							type: 'textbox',
							name: 'username',
							id: 'bctt-username-textbox',
							label: editor.getLang( 'bctt.usernameExplainer', 'Which Twitter username?' ),
							multiline: false,
							value: editor.getLang( 'bctt.userPrePopulated', '' ),
							onkeyup: function() { 
								var tweetLength = jQuery( '#bctt-tweet-textbox' ).val().length;
								tweetLength += 24; // Character count of URL
								if ( 'true' == jQuery( '#bctt-viamark-checkbox' ).attr( 'aria-checked' ) ) {
									tweetLength += jQuery( '#bctt-username-textbox' ).val().length + 6; // Username count plus space & 'via'
								}
								jQuery( '#bctt-text-length p span' ).html( tweetLength );
							}
						},
						{
							type: 'container',
							name: 'container',
							id: 'bctt-text-length',
							html: '<p>' + editor.getLang( 'bctt.totalLengthExplainer', 'Total Length:' ) + ' <span></span></p>'
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