(function ($) {
    // Define a unique namespace for your plugin's functions
    var qapl_quick_ajax_post_loader_admin_scripts = {
        data_available: typeof qapl_quick_ajax_admin_data !== "undefined" && qapl_quick_ajax_admin_data,
        init: function () {
            this.click_and_select_shortcode();
            this.click_and_select_all();
            this.handle_post_type_change();
            this.handle_taxonomy_change();
            this.show_hide_element_on_change();
            this.quick_ajax_tabs();
            this.copy_code();
            this.quick_ajax_function_generator();
            this.accordion_block_toggle();
            // Any other functions you want to initialize
        },
        handle_post_type_change: function () {
            if (!this.data_available) return;
            const self = this;
            const postTypeSelect = $("#" + qapl_quick_ajax_admin_data.constants.quick_ajax_settings_wrapper + " #" + qapl_quick_ajax_admin_data.constants.quick_ajax_post_type);
            if (postTypeSelect.length) {
                postTypeSelect.on("change", function () {
                    const postType = $(this).val();
                    $.ajax({
                        url: qapl_quick_ajax_admin_data.ajax_url,
                        type: "POST",
                        data: {
                            action: "qapl_quick_ajax_get_taxonomies_by_post_type",
                            post_type: postType,
                            nonce: qapl_quick_ajax_admin_data.nonce
                        },
                        success: function (response) {
                            if (response && response.data) {
                                const taxonomySelect = $("#" + qapl_quick_ajax_admin_data.constants.quick_ajax_settings_wrapper + " #" + qapl_quick_ajax_admin_data.constants.quick_ajax_taxonomy);
                                taxonomySelect.empty();
                                taxonomySelect.append(response.data);
                                self.trigger_taxonomy_change();
                            } else {
                                console.error("Quick Ajax Post Loader: Invalid response structure");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Quick Ajax Post Loader:", error);
                        }
                    });
                });
            }
        },
        handle_taxonomy_change: function () {
            if (!this.data_available) return;
            const self = this;
            const taxonomySelect = $("#" + qapl_quick_ajax_admin_data.constants.quick_ajax_settings_wrapper + " #" + qapl_quick_ajax_admin_data.constants.quick_ajax_taxonomy);
            const termsContainer = $("#" + qapl_quick_ajax_admin_data.constants.quick_ajax_settings_wrapper + " #" + qapl_quick_ajax_admin_data.constants.quick_ajax_manual_selected_terms);
            if (taxonomySelect.length) {
                taxonomySelect.on("change", function () {
                    termsContainer.empty();
                    self.admin_page_loader(termsContainer);
                    let taxonomy = $(this).val();
                    let post_id = "";
                    if ($("#post_ID").length) {
                        post_id = $("#post_ID").val();
                    }
                    $.ajax({
                        url: qapl_quick_ajax_admin_data.ajax_url,
                        type: "POST",
                        data: {
                            action: "qapl_quick_ajax_get_terms_by_taxonomy",
                            taxonomy: taxonomy,
                            post_id: post_id,
                            nonce: qapl_quick_ajax_admin_data.nonce
                        },
                        success: function (response) {
                            if (response && response.data) {
                                termsContainer.fadeOut(100, function () {
                                    termsContainer.empty();
                                    termsContainer.append(response.data);
                                    termsContainer.fadeIn(100);
                                });
                            } else {
                                console.error("Quick Ajax Post Loader: Invalid response structure for terms");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Quick Ajax Post Loader:", error);
                        }
                    });
                });
            }
        },
        trigger_taxonomy_change: function () {
            if (!this.data_available) return;
            const self = this;
            const taxonomySelect = $("#" + qapl_quick_ajax_admin_data.constants.quick_ajax_settings_wrapper + " #" + qapl_quick_ajax_admin_data.constants.quick_ajax_taxonomy);
            const termsContainer = $("#" + qapl_quick_ajax_admin_data.constants.quick_ajax_settings_wrapper + " #" + qapl_quick_ajax_admin_data.constants.quick_ajax_manual_selected_terms);
            if (termsContainer.length) {
                self.admin_page_loader(termsContainer);
                taxonomySelect.trigger("change");
            }
        },
        admin_page_loader: function (container) {
            container.append('<div class="qapl-admin-page-loader"><span></span><span></span><span></span></div>');
        },
        show_hide_element_on_change: function () {
            $(".show-hide-trigger input, .show-hide-trigger select").on("change", function () {
                $(".quick-ajax-field-container[data-conditional]").each(function () {
                    const container = $(this);
                    const conditions = container.data("conditional");
                    let shouldBeVisible = true;

                    for (const fieldId in conditions) {
                        if (!conditions.hasOwnProperty(fieldId)) continue;
                        const expectedValue = conditions[fieldId];
                        const triggerField = $("#" + fieldId);
                        const actualValue = triggerField.is(":checkbox") ? (triggerField.is(":checked") ? "1" : "0") : triggerField.val();
                        if (actualValue !== expectedValue) {
                            shouldBeVisible = false;
                            break;
                        }
                    }
                    if (shouldBeVisible) {
                        container.removeClass("inactive");
                    } else {
                        container.addClass("inactive");
                    }
                });
            });
        },
        quick_ajax_tabs: function () {
            // check if there are tabs on the page
            if ($(".quick-ajax-tabs").length) {
                const tabButtons = $(".quick-ajax-tab-button");
                const tabContents = $(".quick-ajax-tab-content");
                // handle click events on tab buttons
                tabButtons.on("click", function (e) {
                    e.preventDefault();
                    const tabId = $(this).data("tab");

                    // deactivate all tabs and buttons
                    tabButtons.removeClass("active").attr("aria-selected", "false").attr("tabindex", "-1");
                    tabContents.removeClass("active").attr("hidden", true);

                    // activate the clicked tab button and its content
                    $(this).addClass("active").attr("aria-selected", "true").attr("tabindex", "0");
                    $("#" + tabId)
                        .addClass("active")
                        .attr("hidden", false);
                });
                // handle keyboard navigation (arrow keys) for tabs
                tabButtons.on("keydown", function (e) {
                    const currentIndex = tabButtons.index(this);
                    let newIndex;
                    if (e.key === "ArrowRight") {
                        newIndex = (currentIndex + 1) % tabButtons.length; // go to next tab
                    } else if (e.key === "ArrowLeft") {
                        newIndex = (currentIndex - 1 + tabButtons.length) % tabButtons.length; // go to prev tab
                    } else {
                        return; // ignore other keys
                    }
                    tabButtons.eq(newIndex).focus().click(); // focus and activate the new tab
                });
            }
        },

        copy_code: function () {
            $(".copy-button").on("click", function () {
                var codeToCopy = $("#" + $(this).data("copy"));
                // Create a temporary textarea
                var tempTextarea = $("<textarea>").val(codeToCopy.text()).appendTo("body").select();
                try {
                    // Use the new clipboard API to copy the selected text
                    navigator.clipboard
                        .writeText(codeToCopy.text())
                        .then(() => {
                            //console.log('Text copied to clipboard');
                        })
                        .catch((error) => {
                            console.error("Quick Ajax - Unable to copy text to clipboard", error);
                        });
                } finally {
                    // Clean up: remove the temporary textarea
                    tempTextarea.remove();
                    // Feedback to the user
                    $(this).text("Code Copied");
                    setTimeout(() => {
                        $(this).text("Copy Code");
                    }, 2000);
                }
            });
        },
        generateId: function (inputDataString) {
            let blockId = 0;
            for (let i = 0; i < inputDataString.length; i++) {
                blockId += inputDataString.charCodeAt(i);
                if (i % 2 === 0) {
                    blockId += inputDataString.charCodeAt(i);
                } else {
                    blockId -= inputDataString.charCodeAt(i);
                }
            }
            return blockId;
        },
        getExcludedPostIds: function (excludedPostIds) {
            var parts = excludedPostIds.split(/[,\s]+/);
            var postNotIn = [];
            parts.forEach(function (part) {
                if (/^\d+$/.test(part) && postNotIn.indexOf(part) === -1) {
                    //postNotIn.indexOf(part) === -1 check and add if id doesn't exist in the array
                    postNotIn.push(part);
                }
            });
            var result = postNotIn.join(", ");
            return result;
        },
        cleanClassNames: function (inputDataString) {
            // Replace commas with spaces
            let cleaned = inputDataString.replace(/,/g, " ");

            // Split string into array of class names
            let classNames = cleaned.split(/\s+/);

            // Filter out class names that start with a digit and remove duplicates
            classNames = classNames.filter((name, index, self) => {
                return !/^\d/.test(name) && name !== "" && self.indexOf(name) === index;
            });

            // Join the cleaned class names with a single space
            return classNames.join(", ");
        },
        qapl_collect_input_data: function () {
            const inputData = {};
            const inputs = $(".function-generator-wrap input, .function-generator-wrap select");
            inputs.each(function (index, input) {
                const $input = $(input);
                const inputName = $input.attr("name");
                const inputId = $input.attr("id");
                if (input.type === "checkbox") {
                    if (inputName && inputName.endsWith("[]")) {
                        // multi-select checkbox field - format name without "[]"
                        let cleanName = inputName.replace(/\[\]$/, "");
                        if (!inputData[cleanName]) {
                            inputData[cleanName] = [];
                        }
                        if ($input.prop("checked")) {
                            inputData[cleanName].push($input.val());
                        }
                    } else {
                        // single checkbox field
                        inputData[inputId] = $input.prop("checked") ? 1 : 0;
                    }
                } else {
                    // standard input/select field
                    inputData[inputId] = $input.val();
                }
            });
            return inputData;
        },
        qapl_generate_args: function (inputData) {
            const self = this;
            //quickAjaxTaxonomy code
            let quickAjaxArgsText = "";
            let quickAjaxSelectedTermsArray = "";
            let formattedItem = "";
            quickAjaxArgsText += "$quick_ajax_args = [\n";
            quickAjaxArgsText += "    'post_type' => '" + inputData.qapl_select_post_type + "',\n";
            //quickAjaxArgsText += "    'post_status' => '" + inputData.qapl_select_post_status + "',\n";
            quickAjaxArgsText += "    'posts_per_page' => " + inputData.qapl_select_posts_per_page + ",\n";
            if (inputData.qapl_select_orderby !== "none") {
                quickAjaxArgsText += "    'orderby' => '" + inputData.qapl_select_orderby + "',\n";
            }
            quickAjaxArgsText += "    'order' => '" + inputData.qapl_select_order + "',\n";
            if (inputData.qapl_select_post_not_in !== "") {
                var excludedPostIds = self.getExcludedPostIds(inputData.qapl_select_post_not_in);
                quickAjaxArgsText += "    'post__not_in' => [" + excludedPostIds + "],\n";
            }
            if (inputData.qapl_ignore_sticky_posts === 1) {
                quickAjaxArgsText += "    'ignore_sticky_posts' => " + inputData.qapl_ignore_sticky_posts + ",\n";
            }
            if (inputData.qapl_show_select_taxonomy === 1) {
                quickAjaxArgsText += "    'selected_taxonomy' => '" + inputData.qapl_select_taxonomy + "',\n";
            }
            if (inputData.qapl_show_select_taxonomy === 1 && inputData.qapl_manual_term_selection === 1) {
                var quickAjaxSelectedTerms = inputData.qapl_manual_selected_terms;
                if (quickAjaxSelectedTerms && quickAjaxSelectedTerms.length > 0) {
                    quickAjaxSelectedTermsArray = "[";
                    quickAjaxSelectedTermsArray += quickAjaxSelectedTerms.map((option) => `${option}`).join(", ");
                    quickAjaxSelectedTermsArray += "]";
                }
                if (quickAjaxSelectedTermsArray) {
                    quickAjaxArgsText += "    'selected_terms' => " + quickAjaxSelectedTermsArray + ",\n";
                }
            }
            let cleanArray = quickAjaxArgsText.trimEnd(); // maybe change to push and join
            if (cleanArray.endsWith(",")) {
                // remove last coma
                quickAjaxArgsText = cleanArray.slice(0, -1) + "\n";
            }
            quickAjaxArgsText += "];";

            if (quickAjaxArgsText.trim() !== "") {
                formattedItem += "\n// Define AJAX query parameters for '" + inputData.qapl_select_post_type + "' type posts.\n";
                formattedItem += quickAjaxArgsText.trim() + "\n";
            }
            return formattedItem;
        },
        qapl_generate_attributes: function (inputData, quick_ajax_id) {
            const self = this;
            let quickAjaxAttributes = {};
            let formattedItem = "";
            quickAjaxAttributes[qapl_quick_ajax_admin_data.constants.quick_ajax_id] = quick_ajax_id;
            if (inputData.qapl_layout_quick_ajax_css_style === 1) {
                quickAjaxAttributes[qapl_quick_ajax_admin_data.constants.quick_ajax_css_style] = inputData.qapl_layout_quick_ajax_css_style;
                quickAjaxAttributes[qapl_quick_ajax_admin_data.constants.grid_num_columns] = inputData.qapl_layout_select_columns_qty;
            }
            if (inputData.qapl_layout_quick_ajax_post_item_template) {
                quickAjaxAttributes[qapl_quick_ajax_admin_data.constants.post_item_template] = inputData.qapl_layout_quick_ajax_post_item_template;
            }
            if (inputData.qapl_layout_add_taxonomy_filter_class && inputData.qapl_layout_add_taxonomy_filter_class !== "") {
                quickAjaxAttributes[qapl_quick_ajax_admin_data.constants.taxonomy_filter_class] = self.cleanClassNames(inputData.qapl_layout_add_taxonomy_filter_class);
            }
            if (inputData.qapl_layout_add_container_class && inputData.qapl_layout_add_container_class !== "") {
                quickAjaxAttributes[qapl_quick_ajax_admin_data.constants.container_class] = self.cleanClassNames(inputData.qapl_layout_add_container_class);
            }
            if (inputData.qapl_show_custom_load_more_post_quantity === 1) {
                quickAjaxAttributes[qapl_quick_ajax_admin_data.constants.load_more_posts] = inputData.qapl_select_custom_load_more_post_quantity;
            }
            if (inputData.qapl_override_global_loader_icon === 1) {
                quickAjaxAttributes[qapl_quick_ajax_admin_data.constants.loader_icon] = inputData.qapl_loader_icon;
            }
            if (inputData.qapl_ajax_on_initial_load === 1) {
                quickAjaxAttributes[qapl_quick_ajax_admin_data.constants.ajax_initial_load] = inputData.qapl_ajax_on_initial_load;
            }
            if (inputData.qapl_ajax_infinite_scroll === 1) {
                quickAjaxAttributes[qapl_quick_ajax_admin_data.constants.infinite_scroll] = inputData.qapl_ajax_infinite_scroll;
            }
            if (inputData.qapl_show_end_post_message === 1) {
                quickAjaxAttributes[qapl_quick_ajax_admin_data.constants.show_end_message] = inputData.qapl_show_end_post_message;
            }
            //quickAjaxAttributes code
            var quickAjaxAttributesText = "";
            if (Object.keys(quickAjaxAttributes).length > 0) {
                quickAjaxAttributesText = "";
                quickAjaxAttributesText += "$quick_ajax_attributes = [\n";
                Object.entries(quickAjaxAttributes).forEach(([key, value]) => {
                    let AttributesValue;
                    // Check if the resulting value is a finite number
                    if (self.quick_ajax_is_numeric(value)) {
                        // Use the numeric value if the conversion was possible
                        AttributesValue = parseInt(value);
                    } else if (typeof value === "string") {
                        // Otherwise, if the value is a string, add quotes
                        AttributesValue = `'${value}'`;
                    } else {
                        // For other data types, use the value unchanged
                        AttributesValue = value;
                    }
                    quickAjaxAttributesText += `    '${key}' => ${AttributesValue},\n`;
                });
                //remove last comma
                quickAjaxAttributesText = quickAjaxAttributesText.slice(0, -2) + "\n";
                quickAjaxAttributesText += "];";
            }
            if (quickAjaxAttributesText.trim() !== "") {
                formattedItem += "\n// Define attributes for AJAX.\n";
                formattedItem += quickAjaxAttributesText.trim() + "\n";
            }
            return formattedItem;
        },
        qapl_generate_sort_controls: function (inputData) {
            //quickAjaxSortControl code
            let quickAjaxSortControl = null;
            let quickAjaxSortControlValueOptions = null;
            if (inputData.qapl_show_order_button === 1) {
                quickAjaxSortControl = inputData.qapl_select_orderby_button_options;
                if (quickAjaxSortControl && quickAjaxSortControl.length > 0) {
                    quickAjaxSortControlValueOptions = "$quick_ajax_sort_options = [";
                    quickAjaxSortControlValueOptions += quickAjaxSortControl.map((option) => `'${option}'`).join(", ");
                    quickAjaxSortControlValueOptions += "];";
                }
            }
            var quickAjaxSortControlValue = "";
            var quickAjaxSortControlText = "";
            if (quickAjaxSortControl !== null && quickAjaxSortControlValueOptions) {
                quickAjaxSortControlValue = "";
                quickAjaxSortControlValue += quickAjaxSortControlValueOptions;
                //qapl_render_sort_controls
                quickAjaxSortControlText = "";
                quickAjaxSortControlText += "if(function_exists('qapl_render_sort_controls')){\n";
                quickAjaxSortControlText += "    qapl_render_sort_controls(\n";
                quickAjaxSortControlText += "        $quick_ajax_args,\n";
                quickAjaxSortControlText += "        $quick_ajax_attributes,\n";
                quickAjaxSortControlText += "        $quick_ajax_sort_options\n";
                quickAjaxSortControlText += "    );\n";
                quickAjaxSortControlText += "}";
            }
            let formattedText = "";
            if (typeof quickAjaxSortControlValue === "string" && quickAjaxSortControlValue.trim() !== "") {
                formattedText += "\n// Set the sort options for the button.\n";
                formattedText += quickAjaxSortControlValue.trim() + "\n";
            }
            if (typeof quickAjaxSortControlText === "string" && quickAjaxSortControlText.trim() !== "") {
                formattedText += "\n// Render the sorting control button.\n";
                formattedText += quickAjaxSortControlText.trim() + "\n";
            }
            return formattedText;
        },
        qapl_generate_taxonomy_filter: function (inputData) {
            let quickAjaxTaxonomy = null;
            let formattedItem = "";
            let quickAjaxTaxonomyFilterText = "";

            if (inputData.qapl_show_select_taxonomy === 1) {
                quickAjaxTaxonomy = inputData.qapl_select_taxonomy;
            }

            if (quickAjaxTaxonomy !== null) {
                //quickAjaxTaxonomyFilterValue += `$quick_ajax_taxonomy = '${quickAjaxTaxonomy}';`;
                //qapl_render_taxonomy_filter
                quickAjaxTaxonomyFilterText += "if(function_exists('qapl_render_taxonomy_filter')){\n";
                quickAjaxTaxonomyFilterText += "    qapl_render_taxonomy_filter(\n";
                quickAjaxTaxonomyFilterText += "        $quick_ajax_args,\n";
                quickAjaxTaxonomyFilterText += "        $quick_ajax_attributes,\n";
                //quickAjaxTaxonomyFilterText += "        $quick_ajax_taxonomy,\n";
                //remove last comma
                quickAjaxTaxonomyFilterText = quickAjaxTaxonomyFilterText.slice(0, -2) + "\n";
                quickAjaxTaxonomyFilterText += "    );\n";
                quickAjaxTaxonomyFilterText += "}";
            }

            if (quickAjaxTaxonomyFilterText.trim() !== "") {
                formattedItem += "\n// Render the navigation for '" + inputData.qapl_select_taxonomy + "' taxonomy.\n";
                formattedItem += quickAjaxTaxonomyFilterText.trim() + "\n";
            }
            return formattedItem;
        },
        qapl_generate_post_container: function (inputData, quickAjaxAttributesText) {
            //qapl_render_post_container
            let formattedItem = "";
            let quick_ajax_post_containerText = "";
            quick_ajax_post_containerText += "if(function_exists('qapl_render_post_container')){\n";
            quick_ajax_post_containerText += "   qapl_render_post_container(\n";
            quick_ajax_post_containerText += "       $quick_ajax_args,\n";
            if (quickAjaxAttributesText !== "") {
                quick_ajax_post_containerText += "       $quick_ajax_attributes,\n";
            }
            //remove last comma
            quick_ajax_post_containerText = quick_ajax_post_containerText.slice(0, -2) + "\n";
            quick_ajax_post_containerText += "   );\n";
            quick_ajax_post_containerText += "}";

            if (quick_ajax_post_containerText.trim() !== "") {
                formattedItem += "\n// Render the grid for '" + inputData.qapl_select_post_type + "' type posts.\n";
                formattedItem += quick_ajax_post_containerText.trim() + "\n";
            }
            return formattedItem;
        },
        quick_ajax_function_generator: function () {
            const self = this;
            if (!this.data_available) return;
            $(".generate-function-button").on("click", function () {
                const button = $(this);
                const outputDiv = button.attr("data-output");
                const targetDiv = $("#" + outputDiv);
                const copyButton = $('.copy-button[data-copy="' + outputDiv + '"]');

                button.prop("disabled", true);
                copyButton.prop("disabled", true);

                const inputData = self.qapl_collect_input_data();
                const inputDataString = Object.values(inputData).join("");
                const quick_ajax_id = self.generateId(inputDataString);

                const quickAjaxArgsText = self.qapl_generate_args(inputData);
                const quickAjaxAttributesText = self.qapl_generate_attributes(inputData, quick_ajax_id);
                const quickAjaxSortControlText = self.qapl_generate_sort_controls(inputData);
                const quickAjaxTaxonomyFilterText = self.qapl_generate_taxonomy_filter(inputData);
                const quick_ajax_post_containerText = self.qapl_generate_post_container(inputData, quickAjaxAttributesText);

                const formattedSections = [quickAjaxArgsText, quickAjaxAttributesText, quickAjaxSortControlText, quickAjaxTaxonomyFilterText, quick_ajax_post_containerText];

                const formattedText = formattedSections.filter(Boolean).join("");

                targetDiv.empty();
                var lines = formattedText.split("\n");
                for (var i = 0; i < lines.length; i++) {
                    (function (index) {
                        setTimeout(function () {
                            targetDiv.append(lines[index] + "\n");
                            //scroll to the line
                            //   targetDiv.scrollTop(targetDiv[0].scrollHeight);
                            if (index === lines.length - 1) {
                                button.prop("disabled", false);
                                copyButton.prop("disabled", false);
                            }
                        }, i * 50);
                    })(i);
                }
            });
        },
        quick_ajax_is_numeric: function (value) {
            return /^-?\d+(\.\d+)?$/.test(value);
        },
        quick_ajax_color_picker: function () {
            $(".color-picker-field").wpColorPicker();
        },
        quick_ajax_select_text: function (element) {
            var range, selection;
            if (document.body.createTextRange) {
                range = document.body.createTextRange();
                range.moveToElementText(element);
                range.select();
            } else if (window.getSelection) {
                selection = window.getSelection();
                range = document.createRange();
                range.selectNodeContents(element);
                selection.removeAllRanges();
                selection.addRange(range);
            }
        },
        click_and_select_shortcode: function () {
            const self = this;
            $(".quick-ajax-shortcode").on("click", function () {
                self.quick_ajax_select_text(this);
            });
        },
        click_and_select_all: function () {
            const self = this;
            $(".click-and-select-all").on("click", function () {
                var code = $(this).find("code").get(0);
                self.quick_ajax_select_text(code);
            });
        },
        accordion_block_toggle: function () {
            // Adjusts min-height of #wpbody-content to fix sticky sidebar issue.
            var wpBodyContent = $("#wpbody-content");
            if (wpBodyContent.find(".quick-ajax-tabs").length > 0) {
                var adminMenuWrapHeight = $("#adminmenuwrap").outerHeight();
                wpBodyContent.css("min-height", adminMenuWrapHeight);
            }
            $(".quick-ajax-accordion-toggle").click(function () {
                $(this).toggleClass("active").next(".quick-ajax-accordion-content").slideToggle(200);
            });
        }
        // Define other functions here
    };

    $(document).ready(function () {
        qapl_quick_ajax_post_loader_admin_scripts.init(); // Initialize all your functions
    });
})(jQuery);
