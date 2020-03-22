/*
 * Post Quick Edit Script
 * Hooks into the inline post editor functionality to extend it to our custom metadata
 */

jQuery(document).ready(
    function ($) {

        //Prepopulating our quick-edit post info
        var $inline_editor = inlineEditPost.edit;
        inlineEditPost.edit = function (id) {
            //call old copy
            $inline_editor.apply(this, arguments);
            //our custom functionality below
            var post_id = 0;
            if (typeof (id) == 'object') {
                post_id = parseInt(this.getId(id));
            }

            //if we have our post
            if (post_id != 0) {
                //find our row
                $row = $('#edit-' + post_id);
                //post_title_hidden setting
                $post_title_hidden_value = $('#post_title_hidden_value' + post_id);
                post_title_hidden_value_value = $post_title_hidden_value.text();
                if(post_title_hidden_value_value == 0) {
                    $row.find('#disable').val(post_title_hidden_value_value).attr('checked', true);
                }
                else if((post_title_hidden_value_value == 1)) {
                    $row.find('#enable').val(post_title_hidden_value_value).attr('checked', true);
                }
            }
        }
    }
);