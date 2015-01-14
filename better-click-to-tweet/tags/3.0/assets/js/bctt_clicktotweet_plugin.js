(function() {
    tinymce.create('tinymce.plugins.bctt_clicktotweet', {
        init: function(ed, url) {
            ed.addButton('bctt_clicktotweet', {
                title: 'Add Tweetable Text',
                image: url.replace("/js", "") + '/img/birdy_button.png',
                onclick: function() {
                    var m = prompt("Add your quote below for readers to tweet.", "Enter the text for readers to tweet");
                    if (m != null && m != 'undefined' && m != 'Enter your tweets' && m != '') ed.execCommand('mceInsertContent', false, '[bctt tweet="' + m + '"]');
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
                version: "1.0"
            };
        }
    });
    tinymce.PluginManager.add('bctt_clicktotweet', tinymce.plugins.bctt_clicktotweet);
})();
