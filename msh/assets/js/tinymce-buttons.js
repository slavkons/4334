jQuery(document).ready(function($) {

    var chord_list = null;
    var action = 'admin_chords_tinymce'

    $.ajax({
            url: ajaxurl + '?action=' + action,
            async: false,   // this is the important line that makes the request synchronous
            type: 'post',
            dataType: 'json',
            success: function(response) {
                chord_list = response;
            }
        }
    );

    function chord_html(id, name) {
        var html = '[' + name + ']';
        return html;
    }

    tinymce.PluginManager.add('admin_chords_script', function(editor, url) {

        /**
         * Add chord buttons
         */
        for (var i = 0; i < chord_list.length; i++) {
            var chord_slug = chord_list[i]['post_name'];
            var chord_title = chord_list[i]['post_title'];
            var chord_id = chord_list[i]['ID'];

            editor.addButton( 'add_chord_' + chord_slug, {
                id: chord_id,
                title : chord_title,
                text: chord_title,
                icon: false,
                onclick: function() {
                    var chord_title = this.settings.title;
                    var chord_id = this.settings.id;
                    editor.insertContent(chord_html(chord_id, chord_title));
                }
            });
        }
    })
});