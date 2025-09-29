(function ($) {
    const qapl_quick_ajax_post_loader_scripts = {
        init: function () {
            this.qapl_quick_ajax_handlers();
            this.qapl_quick_ajax_initial_load();
            this.qapl_quick_ajax_infinite_scroll();
        },
        qapl_quick_ajax_handlers: function () {
            if (typeof qapl_quick_ajax_data !== "undefined" && qapl_quick_ajax_data) {
                const self = this;
                if (qapl_quick_ajax_data.constants.load_more_data_button) {
                    $(".quick-ajax-posts-container").on("click", `[data-button="${qapl_quick_ajax_data.constants.load_more_data_button}"]`, function () {
                        self.qapl_quick_ajax_handle_ajax($(this));
                    });
                }
                if (qapl_quick_ajax_data.constants.filter_data_button) {
                    $(".quick-ajax-filter-container").on("click", `[data-button="${qapl_quick_ajax_data.constants.filter_data_button}"]`, function () {
                        self.qapl_quick_ajax_handle_ajax($(this));
                    });
                }
                if (qapl_quick_ajax_data.constants.sort_button) {
                    $(".quick-ajax-sort-options-container").on("click", `[data-button="${qapl_quick_ajax_data.constants.sort_button}"]`, function () {
                        self.qapl_quick_ajax_handle_ajax($(this));
                    });
                }
                // event listener for sorting
                $("body").on("change", 'select[name="quick_ajax_sort_option"]', function () {
                    self.qapl_quick_ajax_handle_sort($(this));
                });
            }
        },
        qapl_quick_ajax_initial_load: function () {
            const self = this;
            const initialLoader = $(".qapl-initial-loader");
            if (initialLoader.length > 0) {
                //auto load ajax posts on page load
                self.qapl_quick_ajax_handle_ajax(initialLoader);
            }
        },
        qapl_quick_ajax_infinite_scroll: function () {
            const self = this;
            // check if any infinite scroll container exists
            $(".quick-ajax-load-more-container.infinite-scroll").each(function () {
                const observer = new IntersectionObserver(
                    function (entries) {
                        entries.forEach(function (entry) {
                            if (entry.isIntersecting) {
                                const button = $(entry.target).find('button[data-button="' + qapl_quick_ajax_data.constants.load_more_data_button + '"]');
                                if (button.length && !button.hasClass("loading")) {
                                    button.addClass("loading");
                                    button.trigger("click");
                                }
                            }
                        });
                    },
                    {
                        root: null,
                        rootMargin: "0px",
                        threshold: 0.5 // trigger when 50% of element is visible
                    }
                );

                observer.observe(this);
            });
        },
        qapl_quick_ajax_handle_ajax: function (button) {
            const self = this;
            let args = {};
            let attributes = {};
            try {
                args = JSON.parse(button.attr("data-action") || "{}");
                attributes = JSON.parse(button.attr("data-attributes") || "{}");
                if (typeof args !== "object" || typeof attributes !== "object") {
                    throw new Error("Quick Ajax Post Loader: Invalid JSON structure");
                }
            } catch (error) {
                console.error("Quick Ajax Post Loader: Error parsing JSON:", error);
                return;
            }

            const button_type = button.attr("data-button");
            const containerId = attributes[qapl_quick_ajax_data.constants.block_id] || "";
            const container = $("#quick-ajax-" + containerId);
            const container_inner = $("#quick-ajax-" + containerId + " .quick-ajax-posts-wrapper");
            if (!container.length || !container_inner.length) {
                console.error("Quick Ajax Post Loader: Container or inner container not found:", containerId);
                return;
            }
            // remove existing end message if any
            container.find(".quick-ajax-end-message-container").remove();
            container.addClass("loading");
            //set container height to first item height to prevent layout shift
            if (container.hasClass("quick-ajax-theme")) {
                const firstItem = container_inner.find(".qapl-post-item:first");
                if (firstItem.length) {
                    container.css("min-height", firstItem.outerHeight() + "px");
                }
            }
            if (button_type === qapl_quick_ajax_data.constants.filter_data_button || button_type === qapl_quick_ajax_data.constants.sort_button) {
                container.addClass("filter-update");
                container_inner.fadeOut(100, function () {
                    $(this).empty().fadeIn(100);
                });
            }
            $.ajax({
                url: qapl_quick_ajax_data.ajax_url,
                type: "POST",
                data: {
                    action: "qapl_quick_ajax_load_posts",
                    nonce: qapl_quick_ajax_data.nonce,
                    args: args,
                    attributes: attributes,
                    button_type: button_type
                },
                success: function (response) {
                    if (response && response.data) {
                        if (button_type === qapl_quick_ajax_data.constants.load_more_data_button) {
                            self.qapl_quick_ajax_load_more_add_posts(container_inner, button, response.data.output);
                        } else if (button_type === qapl_quick_ajax_data.constants.filter_data_button || button_type === qapl_quick_ajax_data.constants.sort_button) {
                            self.qapl_quick_ajax_taxonomy_filter_show_posts(container_inner, button, response.data.output, containerId);
                        }
                        self.qapl_quick_ajax_append_load_more_button(container_inner, response.data.load_more);
                    } else {
                        console.error("Quick Ajax Post Loader: Error:", response.data.output);
                    }
                    container.removeClass("loading");
                    setTimeout(function () {
                        container.removeClass("filter-update");
                    }, 200);

                    self.qapl_quick_ajax_append_end_message(container, response.data.show_end_message);
                },
                error: function (xhr, status, error) {
                    console.error("Quick Ajax Post Loader: Error:", error);
                    container.removeClass("loading");
                    setTimeout(function () {
                        container.removeClass("filter-update");
                    }, 200);
                }
            });
        },
        qapl_quick_ajax_load_more_add_posts: function (container, button, response) {
            button.parent().remove();
            const new_posts = $(response).hide();
            container.append(new_posts);
            new_posts.slideDown(function () {
                $(this).removeAttr("style");
            });
        },
        qapl_quick_ajax_taxonomy_filter_show_posts: function (container, button, response, containerId) {
            let filterContainer = $("#quick-ajax-filter-" + containerId);
            filterContainer.find(`[data-button="${qapl_quick_ajax_data.constants.filter_data_button}"]`).removeClass("active");
            button.addClass("active");
            container.parent().find(".quick-ajax-load-more-container").remove();
            container.stop(true, true).fadeOut(100, function () {
                const new_posts = $(response).css("opacity", "0");
                container.html(new_posts).fadeIn(400);
                new_posts.animate(
                    { opacity: 1 },
                    {
                        duration: 400,
                        complete: function () {
                            $(this).removeAttr("style");
                        }
                    }
                );
            });
        },
        qapl_quick_ajax_append_load_more_button: function (container, load_more_html) {
            if (load_more_html) {
                container.parent().find(".quick-ajax-load-more-container").remove();
                container.parent().append(load_more_html);
                this.qapl_quick_ajax_infinite_scroll();
            }
        },
        qapl_quick_ajax_append_end_message: function (container, end_message) {
            if (end_message) {
                container.append(end_message);
            }
        },
        qapl_quick_ajax_handle_sort: function (selectButton) {
            const sortContainer = selectButton.closest(".quick-ajax-sort-options-container");
            const querySettings = sortContainer.find(".quick-ajax-settings");
            const settingsData = querySettings.data("attributes");

            // check if quick_ajax_id exists
            if (!settingsData || !settingsData.quick_ajax_id) {
                return; // stop if quick_ajax_id is missing
            }

            const quickAjaxId = settingsData.quick_ajax_id;
            const filterContainer = $("#quick-ajax-filter-" + quickAjaxId);
            // get selected value
            const selectedValue = selectButton.val();
            const [orderby = "", order = ""] = selectedValue.split("-");
            // update quick-ajax-settings in the same sort container
            let actionData = querySettings.data("action");
            // convert to object if needed
            if (typeof actionData === "string") {
                try {
                    actionData = JSON.parse(actionData);
                } catch (e) {
                    console.error("Quick Ajax Post Loader: Invalid JSON in sort settings");
                    return;
                }
            }
            // change orderby and order
            actionData.orderby = orderby;
            actionData.order = order;
            //update data-action
            querySettings.attr("data-action", JSON.stringify(actionData));
            // if filter container does not exist, trigger only settings span click
            if (!filterContainer.length) {
                if (querySettings.is("[data-action]")) {
                    querySettings.trigger("click");
                }
                return;
            }
            // update all filter buttons in the matching filter container
            filterContainer.find(".qapl-filter-button").each(function () {
                const button = $(this);
                let actionData = button.data("action");
                // get action data
                if (typeof actionData === "string") {
                    try {
                        actionData = JSON.parse(actionData);
                    } catch (e) {
                        console.error("Quick Ajax Post Loader: Invalid JSON in filter button");
                        return;
                    }
                }
                // convert to object if needed
                actionData.orderby = orderby;
                actionData.order = order;
                button.attr("data-action", JSON.stringify(actionData));
            });
            // find the active button
            const activeButton = filterContainer.find(".qapl-filter-button.active");

            if (activeButton.length) {
                // click active button if exists
                activeButton.trigger("click");
            } else if (querySettings.is("[data-action]")) {
                //click settings span
                querySettings.trigger("click");
            }
        }
    };

    $(document).ready(function () {
        qapl_quick_ajax_post_loader_scripts.init();
    });
})(jQuery);
