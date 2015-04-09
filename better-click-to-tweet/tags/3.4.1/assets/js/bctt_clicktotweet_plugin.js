(function() {
    tinymce.create('tinymce.plugins.bctt_clicktotweet', {
        init: function(ed, url) {
            ed.addButton('bctt_clicktotweet', {
                title: 'Add Tweetable Text',
                image: url.replace("/js", "") + '/img/birdy_button.png',
                onclick: function() {
                	var width = jQuery(window).width(), H = jQuery(window).height(), W = ( 720 < width ) ? 720 : width;
                		W = W - 10;
					tb_show( 'Better Click To Tweet Shortcode Generator', '#TB_inline?width=' + W + '&height=auto' + '&inlineId=bctt-form' );
                }
            });
        },
        createControl: function(n, cm) {
            return null;
        },
        getInfo: function() {
            return {
                longname: "Click To Tweet by BenUNC",
                author: 'Ben Meredith',
                authorurl: 'http://benandjacq.com/',
                infourl: 'http://benandjacq.com/better-click-to-tweet',
                version: "2.0"
            };
        }
    });
    tinymce.PluginManager.add('bctt_clicktotweet', tinymce.plugins.bctt_clicktotweet);


jQuery(function(){
		// creates a form to be displayed everytime the button is clicked
		var form = jQuery('<div id="bctt-form"><table id="bctt-table" class="form-table">\
			<tr>\
				<th><label for="bctt-tweet">Tweetable Quote</label></th>\
				<td><p><textarea cols="70" id="bctt-tweet" name="tweet" /></p>\
				<small>Enter the Tweet. Text will be automatically truncated to provide space for the link back to the post and (optional) your Twitter user name. </small></td>\
			</tr>\
			<tr>\
				<th><label for="bctt-via">Include "via"?</label></th>\
				<td><p><input type="radio" name="viamark" id="via" value="yes" checked />Yes\
				<input type="radio" name="viamark" id="via" value="no" />No</br /></p>\
				<small>Do you wand to add \"via @YourTwitterName\" to this tweet?</small></td>\
			</tr>\
			</table>\
			<p class="submit">\
			<input type="button" id="bctt-submit" class="button-primary" value="Insert Tweet" name="submit" />\
			</p>\
			</div>');
		
		var table = form.find('table');
		form.appendTo('body').hide();
		
		// handles the click event of the submit button
		form.find('#bctt-submit').click(function(){
			// defines the options and their default values
			
			var shortcode = '[bctt';
			
			var value = table.find('#bctt-tweet').val();
			
			if ( value != '' ) {
				shortcode += ' tweet="' + value + '"';
				}
			
			var viaChoice = table.find('input[name="viamark"]:checked').val();
			
			if ( viaChoice == "no" ) {
				shortcode += ' via="no"';
				}
				
			shortcode += ']';
			
			// inserts the shortcode into the active editor
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			
			// closes Thickbox
			tb_remove();
		});
	});
})();