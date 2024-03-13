
(function($) {
    var WpgQuickAjaxPostLoaderScripts = {
        init: function() {
            this.quickAjaxHandlers();
        },
        quickAjaxHandlers: function() {
            if (typeof quick_ajax !== 'undefined' && quick_ajax) {
                var self = this;
                $('.quick-ajax-posts-wrapper').on('click', `[data-button="${quick_ajax.helper.load_more_data_button}"]`, function() {
                    self.quickAjaxHandleAjax($(this));
                });
                $('.quick-ajax-filter-wrapper').on('click', `[data-button="${quick_ajax.helper.filter_data_button}"]`, function() {
                    self.quickAjaxHandleAjax($(this));
                });
            }
        },
        quickAjaxHandleAjax: function(button) {
            var self = this;
            try {
                var args = JSON.parse(button.attr('data-action') || '{}');
                var attributes = JSON.parse(button.attr('data-attributes') || '{}');
            } catch (error) {
                console.error('Quick Ajax - Error parsing JSON:', error);
                return;
            }

            var button_type = button.attr('data-button');
            var containerId = attributes[quick_ajax.helper.block_id] || '';
            var container = $('#' + containerId);
            var containerInner = $('#' + containerId + ' .quick-ajax-posts-inner-wrapper');
            if (!container.length) {
                console.error('Quick Ajax - Container not found:', containerId);
                return;
            }

            container.addClass('loading');
            if(button.attr('data-button') === quick_ajax.helper.filter_data_button){
                containerInner.fadeOut(100, function() {
                    $(this).empty().fadeIn(100);
                });
            }

            $.ajax({
                url: quick_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'quick_ajax_load_posts',
                    args: args,
                    attributes: attributes,
                    button_type: button_type,
                },
                success: (response) => {
                    if(button_type === quick_ajax.helper.load_more_data_button){
                        self.quickAjaxLoadMoreAddPosts(containerInner, button, response);
                    } else if(button_type === quick_ajax.helper.filter_data_button){
                        self.quickAjaxTermFilterShowPosts(containerInner, button, response);
                    }
                    container.removeClass('loading');
                },
                error: function(xhr, status, error) {
                    console.error('Quick Ajax - Error:', error);
                    container.removeClass('loading');
                }
            });
        },
        quickAjaxLoadMoreAddPosts: function(container, button, response) {
            button.parent().remove();
            var new_posts = $(response).hide();
            container.append(new_posts);
            new_posts.slideDown(function() {
                $(this).removeAttr("style");
            });
        },
        quickAjaxTermFilterShowPosts: function(container, button, response) {
            $(`[data-button="${quick_ajax.helper.filter_data_button}"]`).removeClass('active');
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
        WpgQuickAjaxPostLoaderScripts.init();
    });
})(jQuery);
  
