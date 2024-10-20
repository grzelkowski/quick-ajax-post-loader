
(function($) {
    var qapl_quick_ajax_post_loader_scripts = {
        init: function() {
            this.qapl_quick_ajax_handlers();
        },
        qapl_quick_ajax_handlers: function() {
            if (typeof qapl_quick_ajax_helper !== 'undefined' && qapl_quick_ajax_helper) {
                var self = this;
                if (qapl_quick_ajax_helper.helper.load_more_data_button) {
                    $('.quick-ajax-posts-wrapper').on('click', `[data-button="${qapl_quick_ajax_helper.helper.load_more_data_button}"]`, function() {
                        self.qapl_quick_ajax_handle_ajax($(this));
                    });
                }
                if (qapl_quick_ajax_helper.helper.filter_data_button) {
                    $('.quick-ajax-filter-wrapper').on('click', `[data-button="${qapl_quick_ajax_helper.helper.filter_data_button}"]`, function() {
                        self.qapl_quick_ajax_handle_ajax($(this));
                    });
                }
            }
        },
        qapl_quick_ajax_handle_ajax: function(button) {
            var self = this;
            try {
                var args = JSON.parse(button.attr('data-action') || '{}');
                var attributes = JSON.parse(button.attr('data-attributes') || '{}');
                if (typeof args !== 'object' || typeof attributes !== 'object') {
                    throw new Error('Quick Ajax Post Loader: Invalid JSON structure');
                }
            } catch (error) {
                console.error('Quick Ajax Post Loader: Error parsing JSON:', error);
                return;
            }

            var button_type = button.attr('data-button');
            var containerId = attributes[qapl_quick_ajax_helper.helper.block_id] || '';
            var container = $('#' + containerId);
            var container_inner = $('#' + containerId + ' .quick-ajax-posts-inner-wrapper');
            if (!container.length || !container_inner.length) {
                console.error('Quick Ajax Post Loader: Container or inner container not found:', containerId);
                return;
            }            

            container.addClass('loading');
            if(button.attr('data-button') === qapl_quick_ajax_helper.helper.filter_data_button){
                container_inner.fadeOut(100, function() {
                    $(this).empty().fadeIn(100);
                });
            }
            $.ajax({
                url: qapl_quick_ajax_helper.ajax_url,
                type: 'POST',
                data: {
                    action: 'qapl_quick_ajax_load_posts',
                    nonce: qapl_quick_ajax_helper.nonce,
                    args: args,
                    attributes: attributes,
                    button_type: button_type,
                },
                success: function(response) {
                    if (response && response.data) {
                        if(button_type === qapl_quick_ajax_helper.helper.load_more_data_button){
                            self.qapl_quick_ajax_load_more_add_posts(container_inner, button, response.data.output);
                        } else if(button_type === qapl_quick_ajax_helper.helper.filter_data_button){
                            self.qapl_quick_ajax_term_filter_show_posts(container_inner, button, response.data.output);
                        }
                    } else {
                        console.error('Quick Ajax Post Loader: Error:', response.data.output);
                    }
                    container.removeClass('loading');
                },
                error: function(xhr, status, error) {
                    console.error('Quick Ajax Post Loader: Error:', error);
                    container.removeClass('loading');
                }
            });
        },
        qapl_quick_ajax_load_more_add_posts: function(container, button, response) {
            button.parent().remove();
            var new_posts = $(response).hide();
            container.append(new_posts);
            new_posts.slideDown(function() {
                $(this).removeAttr("style");
            });
        },
        qapl_quick_ajax_term_filter_show_posts: function(container, button, response) {
            $(`[data-button="${qapl_quick_ajax_helper.helper.filter_data_button}"]`).removeClass('active');
            button.addClass('active');
            var new_posts = $(response).css('opacity', '0');
            container.html(new_posts);
            new_posts.animate({ opacity: 1 }, {
                duration: 400,
                complete: function() {
                    $(this).removeAttr('style');
                }
            });
        }
    };

    $(document).ready(function() {
        qapl_quick_ajax_post_loader_scripts.init();
    });
})(jQuery);
  
