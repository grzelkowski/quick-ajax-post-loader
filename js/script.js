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
                    // remember the term selected on page load, to return to it when the search field is cleared
                    $(".quick-ajax-filter-container").each(function () {
                        const container = $(this);
                        const buttons = container.find(".qapl-filter-button");
                        container.data("qaplDefaultActive", buttons.index(buttons.filter(".active")));
                    });
                    $(".quick-ajax-filter-container").on("click", `[data-button="${qapl_quick_ajax_data.constants.filter_data_button}"]`, function () {
                        const button = $(this);
                        // taxonomy and search exclude each other - picking a term drops the phrase
                        self.qapl_quick_ajax_clear_search(button);
                        self.qapl_quick_ajax_handle_ajax(button);
                    });
                }
                if (qapl_quick_ajax_data.constants.sort_button) {
                    $(".quick-ajax-sort-options-container").on("click", `[data-button="${qapl_quick_ajax_data.constants.sort_button}"]`, function () {
                        self.qapl_quick_ajax_handle_ajax($(this));
                    });
                }
                if (qapl_quick_ajax_data.constants.search_button) {
                    $(".quick-ajax-search-container").on("click", `[data-button="${qapl_quick_ajax_data.constants.search_button}"]`, function () {
                        self.qapl_quick_ajax_handle_ajax($(this));
                    });
                }
                // event listener for sorting
                $("body").on("change", 'select[name="quick_ajax_sort_option"]', function () {
                    self.qapl_quick_ajax_handle_sort($(this));
                });

                // event listeners for search
                $("body").on("keyup", ".qapl-search-input", function (e) {
                    const input = $(this);
                    clearTimeout(input.data("qaplSearchTimer"));
                    // enter - search immediately
                    if (e.key === "Enter") {
                        self.qapl_quick_ajax_handle_search(input);
                        return;
                    }
                    const phrase = (input.val() || "").trim();
                    // auto search from 4 characters, or when the field is cleared
                    if (phrase.length > 0 && phrase.length <= 3) {
                        return;
                    }
                    input.data(
                        "qaplSearchTimer",
                        setTimeout(function () {
                            self.qapl_quick_ajax_handle_search(input);
                        }, 400)
                    );
                });
                $("body").on("click", ".qapl-search-submit", function () {
                    const input = $(this).closest(".quick-ajax-search-wrapper").find(".qapl-search-input");
                    clearTimeout(input.data("qaplSearchTimer"));
                    self.qapl_quick_ajax_handle_search(input);
                });
            }
        },
        qapl_quick_ajax_initial_load: function () {
            const self = this;
            const initialLoaders = $(".qapl-initial-loader");
            if (initialLoaders.length > 0) {
                initialLoaders.each(function () {
                    const loader = $(this);
                    self.qapl_quick_ajax_handle_ajax(loader);
                });
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
            if (button.hasClass("loading")) {
                return;
            }
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
            button.addClass("loading");
            //set container height to first item height to prevent layout shift
            if (container.hasClass("quick-ajax-theme")) {
                const firstItem = container_inner.find(".qapl-post-item:first");
                if (firstItem.length) {
                    container.css("min-height", firstItem.outerHeight() + "px");
                }
            }
            if (button_type === qapl_quick_ajax_data.constants.filter_data_button || button_type === qapl_quick_ajax_data.constants.sort_button || button_type === qapl_quick_ajax_data.constants.search_button) {
                container.addClass("filter-update");
                container_inner.fadeOut(100, function () {
                    $(this).empty().fadeIn(100);
                });
            }
            $.ajax({
                url: qapl_quick_ajax_data.ajax_url,
                type: "POST",
                data: {
                    action: "qapl_action_load_posts",
                    nonce: qapl_quick_ajax_data.nonce,
                    args: args,
                    attributes: attributes,
                    button_type: button_type
                },
                success: function (response) {
                    if (response && response.success && response.data) {
                        if (button_type === qapl_quick_ajax_data.constants.load_more_data_button) {
                            self.qapl_quick_ajax_load_more_add_posts(container_inner, button, response.data.output);
                        } else if (button_type === qapl_quick_ajax_data.constants.filter_data_button || button_type === qapl_quick_ajax_data.constants.sort_button || button_type === qapl_quick_ajax_data.constants.search_button) {
                            self.qapl_quick_ajax_taxonomy_filter_show_posts(container_inner, button, response.data.output, containerId);
                        }
                        self.qapl_quick_ajax_append_load_more_button(container_inner, response.data.load_more);
                        self.qapl_quick_ajax_append_end_message(container, response.data.show_end_message);
                    } else {
                        const errorMessage = response && response.data && response.data.message ? response.data.message : "Unexpected response";
                        console.error("Quick Ajax Post Loader: Error:", errorMessage);
                    }
                    container.removeClass("loading");
                    setTimeout(function () {
                        container.removeClass("filter-update");
                    }, 200);
                },
                error: function (xhr, status, error) {
                    console.error("Quick Ajax Post Loader: Error:", error);
                    container.removeClass("loading");
                    setTimeout(function () {
                        container.removeClass("filter-update");
                    }, 200);
                },
                complete: function () {
                    button.removeClass("loading");
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
            //remove active classes if real filter button, not the initial loader
            if (!button.hasClass("qapl-initial-loader")) {
                filterContainer.find(`[data-button="${qapl_quick_ajax_data.constants.filter_data_button}"]`).removeClass("active");
            }
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
        qapl_quick_ajax_parse_action_data: function (element) {
            let actionData = element.data("action");
            if (typeof actionData === "string") {
                try {
                    actionData = JSON.parse(actionData);
                } catch (e) {
                    console.error("Quick Ajax Post Loader: Invalid JSON in data-action");
                    return null;
                }
            }
            return actionData && typeof actionData === "object" ? actionData : null;
        },
        qapl_quick_ajax_sync_action_data: function (quickAjaxId, patch) {
            // every control of the same instance carries its own copy of the query args,
            // so a change made in one control has to be written to all of them
            const self = this;
            const carriers = $("#quick-ajax-sort-options-" + quickAjaxId + " .quick-ajax-settings")
                .add("#quick-ajax-search-" + quickAjaxId + " .quick-ajax-settings")
                .add("#quick-ajax-filter-" + quickAjaxId + " .qapl-filter-button");
            carriers.each(function () {
                const carrier = $(this);
                const actionData = self.qapl_quick_ajax_parse_action_data(carrier);
                if (!actionData) {
                    return;
                }
                $.extend(actionData, patch);
                carrier.attr("data-action", JSON.stringify(actionData));
            });
        },
        qapl_quick_ajax_trigger_reload: function (quickAjaxId, fallbackSettings) {
            const activeButton = $("#quick-ajax-filter-" + quickAjaxId).find(".qapl-filter-button.active");
            if (activeButton.length) {
                activeButton.trigger("click");
                return;
            }
            if (fallbackSettings.is("[data-action]")) {
                fallbackSettings.trigger("click");
            }
        },
        qapl_quick_ajax_reset_taxonomy_filter: function (quickAjaxId) {
            // a search phrase returns posts from the whole post type, so no term stays selected
            $("#quick-ajax-filter-" + quickAjaxId)
                .find(".qapl-filter-button")
                .removeClass("active");
        },
        qapl_quick_ajax_clear_search: function (button) {
            // called when a term is picked - the phrase is dropped before the request is built
            const settingsData = button.data("attributes");
            if (!settingsData || !settingsData.quick_ajax_id) {
                return;
            }
            const quickAjaxId = settingsData.quick_ajax_id;
            const searchInput = $("#quick-ajax-search-" + quickAjaxId).find(".qapl-search-input");
            if (!searchInput.length || (searchInput.val() || "") === "") {
                return;
            }
            // drop a pending debounce, otherwise the cleared phrase would come back
            clearTimeout(searchInput.data("qaplSearchTimer"));
            searchInput.val("");
            this.qapl_quick_ajax_sync_action_data(quickAjaxId, { s: "" });
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
            // get selected value
            const selectedValue = selectButton.val();
            const [orderby = "", order = ""] = selectedValue.split("-");

            this.qapl_quick_ajax_sync_action_data(quickAjaxId, { orderby: orderby, order: order });
            this.qapl_quick_ajax_trigger_reload(quickAjaxId, querySettings);
        },
        qapl_quick_ajax_handle_search: function (searchInput) {
            const searchContainer = searchInput.closest(".quick-ajax-search-container");
            const querySettings = searchContainer.find(".quick-ajax-settings");
            const settingsData = querySettings.data("attributes");

            // check if quick_ajax_id exists
            if (!settingsData || !settingsData.quick_ajax_id) {
                return; // stop if quick_ajax_id is missing
            }

            const quickAjaxId = settingsData.quick_ajax_id;
            // get search phrase
            const phrase = (searchInput.val() || "").trim();
            const currentData = this.qapl_quick_ajax_parse_action_data(querySettings);
            if (!currentData) {
                return;
            }
            // skip if the phrase has not changed
            if ((currentData.s || "") === phrase) {
                return;
            }

            this.qapl_quick_ajax_sync_action_data(quickAjaxId, { s: phrase });
            this.qapl_quick_ajax_reset_taxonomy_filter(quickAjaxId);

            if (phrase !== "") {
                // taxonomy is reset, so the search settings span drives the request
                if (querySettings.is("[data-action]")) {
                    querySettings.trigger("click");
                }
                return;
            }
            // empty field - back to the term selected on page load ("Show All" when it is displayed)
            const filterContainer = $("#quick-ajax-filter-" + quickAjaxId);
            const filterButtons = filterContainer.find(".qapl-filter-button");
            const defaultIndex = filterContainer.data("qaplDefaultActive");
            if (typeof defaultIndex === "number" && defaultIndex >= 0 && filterButtons.length) {
                filterButtons.eq(defaultIndex).trigger("click");
                return;
            }
            if (querySettings.is("[data-action]")) {
                querySettings.trigger("click");
            }
        }
    };

    $(document).ready(function () {
        qapl_quick_ajax_post_loader_scripts.init();
    });
})(jQuery);
