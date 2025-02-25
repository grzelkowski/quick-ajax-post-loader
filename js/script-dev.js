(function($) {
    var qapl_quick_ajax_post_loader_scripts = {
        init: function() {
            this.qapl_quick_ajax_handlers();
            this.qapl_quick_ajax_initial_load();
        },
        qapl_quick_ajax_handlers: function() {
            if (typeof qapl_quick_ajax_helper !== 'undefined' && qapl_quick_ajax_helper) {
                var self = this;
                if (qapl_quick_ajax_helper.helper.load_more_data_button) {
                    $('.quick-ajax-posts-container').on('click', `[data-button="${qapl_quick_ajax_helper.helper.load_more_data_button}"]`, function() {
                        self.qapl_quick_ajax_handle_ajax($(this));
                    });
                }
                if (qapl_quick_ajax_helper.helper.filter_data_button) {
                    $('.quick-ajax-filter-container').on('click', `[data-button="${qapl_quick_ajax_helper.helper.filter_data_button}"]`, function() {
                        self.qapl_quick_ajax_handle_ajax($(this));
                    });
                }
                if (qapl_quick_ajax_helper.helper.sort_button) {
                    $('.quick-ajax-sort-options-container').on('click', `[data-button="${qapl_quick_ajax_helper.helper.sort_button}"]`, function() {
                        self.qapl_quick_ajax_handle_ajax($(this));
                    });
                }
                // event listener for sorting
                $('body').on('change', 'select[name="quick_ajax_sort_option"]', function() {
                    self.qapl_quick_ajax_handle_sort($(this));
                });
            }
        },
        qapl_quick_ajax_initial_load: function() {
            var self = this;
            var initialLoader = $('.qapl-initial-loader');    
            if (initialLoader.length > 0) {
                //auto load ajax posts on page load
                self.qapl_quick_ajax_handle_ajax(initialLoader);
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
            var container = $('#quick-ajax-' + containerId);
            var container_inner = $('#quick-ajax-' + containerId + ' .quick-ajax-posts-wrapper');
            if (!container.length || !container_inner.length) {
                console.error('Quick Ajax Post Loader: Container or inner container not found:', containerId);
                return;
            }            

            container.addClass('loading');
            if((button.attr('data-button') === qapl_quick_ajax_helper.helper.filter_data_button) ||
               (button.attr('data-button') === qapl_quick_ajax_helper.helper.sort_button)){
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
                        } else if((button.attr('data-button') === qapl_quick_ajax_helper.helper.filter_data_button) || (button.attr('data-button') === qapl_quick_ajax_helper.helper.sort_button)){
                            self.qapl_quick_ajax_taxonomy_filter_show_posts(container_inner, button, response.data.output);
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
        qapl_quick_ajax_taxonomy_filter_show_posts: function(container, button, response) {
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
        },
        qapl_quick_ajax_handle_sort: function(selectButton) {
            let $sortContainer = selectButton.closest('.quick-ajax-sort-options-container');
            let $QuerySettings = $sortContainer.find('.quick-ajax-settings');
            let settingsData = $QuerySettings.data('attributes');

            // check if quick_ajax_id exists
            if (!settingsData || !settingsData.quick_ajax_id) {
                return; // stop if quick_ajax_id is missing
            }

            let quickAjaxId = settingsData.quick_ajax_id;
            let $filterContainer = $('#quick-ajax-filter-' + quickAjaxId);
            // get selected value
            let selectedValue = selectButton.val();
            let [orderby, order] = selectedValue.split('-');
            // update quick-ajax-settings in the same sort container
            let actionData = $QuerySettings.data('action');
            // convert to object if needed
            if (typeof actionData === 'string') {
                actionData = JSON.parse(actionData);
            }
            // change orderby and order
            actionData.orderby = orderby;
            actionData.order = order;
            //update data-action
            $QuerySettings.attr('data-action', JSON.stringify(actionData));
            // if filter container does not exist, trigger only settings span click
            if (!$filterContainer.length) {
                if ($QuerySettings.is('[data-action]')) {
                    $QuerySettings.trigger('click');
                }
                return;
            }
            // update all filter buttons in the matching filter container
            $filterContainer.find('.qapl-filter-button').each(function () {
                let $button = $(this);
                let actionData = $button.data('action');
                // get action data
                if (typeof actionData === 'string') {
                    actionData = JSON.parse(actionData);
                }
                // convert to object if needed
                actionData.orderby = orderby;
                actionData.order = order;
                $button.attr('data-action', JSON.stringify(actionData));
            });
            // find the active button
            let $activeButton = $filterContainer.find('.qapl-filter-button.active');

            if ($activeButton.length) {
                // click active button if exists
                $activeButton.trigger('click');
            } else {
                 //click settings span
                if ($QuerySettings.is('[data-action]')) {
                    $QuerySettings.trigger('click');
                }
            }
        }
    };

    $(document).ready(function() {
        qapl_quick_ajax_post_loader_scripts.init();
    });
})(jQuery);