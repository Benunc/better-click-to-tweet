(function() {
    tinymce.create('tinymce.plugins.bctt_clicktotweet', {
        init: function(ed, url) {
            ed.addButton('bctt_clicktotweet', {
                title: 'Test Title',
                image: url.replace("/js", "") + '/img/birdy_button.png',
                onclick: function() {
                    var outerPrompt = ed.getLang('extrastrings.title');
                    var m = prompt( ( "Tweety" , "Enter the text for readers to tweet");
                    if (m != null && m != 'undefined' && m != 'Enter the text for readers to tweet' && m != '') ed.execCommand('mceInsertContent', false, '[bctt tweet="' + m + '"]');
                }
            });
        },
        createControl: function(n, cm) {
            return null;
        },
        getInfo: function() {
            return {
                longname: "Bettter Click To Tweet by Ben Meredith",
                author: 'Ben Meredith',
                authorurl: 'http://benandjacq.com/',
                infourl: 'http://wordpress.org/plugins/better-click-to-tweet',
                version: "1.0"
            };
        }
    });
    tinymce.PluginManager.add('bctt_clicktotweet', tinymce.plugins.bctt_clicktotweet);
})();
