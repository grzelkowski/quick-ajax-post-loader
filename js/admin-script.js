(function ($) {
    // Define a unique namespace for your plugin's functions
    var qapl_quick_ajax_post_loader_admin_scripts = {
        init: function () {
            this.click_and_select_shortcode();
            this.click_and_select_all();
            this.handle_post_type_change();
            this.show_hide_element_on_change();
            this.quick_ajax_tabs();
            this.copy_code();
            this.quick_ajax_function_generator();
            this.accordion_block_toggle();
            // Any other functions you want to initialize
        },
        handle_post_type_change: function () {
            if (typeof qapl_quick_ajax_helper !== "undefined" && qapl_quick_ajax_helper) {
                if ($("#" + qapl_quick_ajax_helper.quick_ajax_settings_wrapper + " #" + qapl_quick_ajax_helper.quick_ajax_post_type).length) {
                    $("#" + qapl_quick_ajax_helper.quick_ajax_settings_wrapper + " #" + qapl_quick_ajax_helper.quick_ajax_post_type).on("change", function () {
                        var postType = $(this).val();
                        $.ajax({
                            url: qapl_quick_ajax_helper.ajax_url,
                            type: "POST",
                            data: {
                                action: "qapl_quick_ajax_get_taxonomies_by_post_type",
                                post_type: postType,
                                nonce: qapl_quick_ajax_helper.nonce
                            },
                            success: function (response) {
                                if (response && response.data) {
                                    var taxonomySelect = $("#" + qapl_quick_ajax_helper.quick_ajax_settings_wrapper + " #" + qapl_quick_ajax_helper.quick_ajax_taxonomy);
                                    taxonomySelect.empty();
                                    taxonomySelect.append(response.data);
                                } else {
                                    console.error("Quick Ajax Post Loader: Invalid response structure");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error(error);
                            }
                        });
                    });
                }
            }
        },
        show_hide_element_on_change: function () {
            $(".show-hide-element").each(function () {
                var checkbox = $(this).find("input");
                var targetID = checkbox.attr("id");
                checkbox.change(function () {
                    if (checkbox.is(":checked")) {
                        $('div[data-item="' + targetID + '"]').removeClass("inactive");
                    } else {
                        $('div[data-item="' + targetID + '"]').addClass("inactive");
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
            return classNames.join(" ");
        },
        quick_ajax_function_generator: function () {
            var self = this;
            $(".generate-function-button").on("click", function () {
                var button = $(this);
                var outputDiv = button.attr("data-output");
                button.prop("disabled", true);
                var copyButton = $('.copy-button[data-copy="' + outputDiv + '"]');
                copyButton.prop("disabled", true);
                var inputData = {};
                var inputs = $(".function-generator-wrap input, .function-generator-wrap select");
                inputs.each(function (index, input) {
                    var $input = $(input);
                    var inputName = $input.attr("name");
                    var inputId = $input.attr("id");
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
                let inputDataString = Object.values(inputData).join("");
                //quickAjaxArgs code
                var quickAjaxArgsText = "";
                quickAjaxArgsText += "$quick_ajax_args = array(\n";
                quickAjaxArgsText += "    'post_type' => '" + inputData.qapl_select_post_type + "',\n";
                //quickAjaxArgsText += "    'post_status' => '" + inputData.qapl_select_post_status + "',\n";
                quickAjaxArgsText += "    'posts_per_page' => " + inputData.qapl_select_posts_per_page + ",\n";
                if (inputData.qapl_select_orderby !== "none") {
                    quickAjaxArgsText += "    'orderby' => '" + inputData.qapl_select_orderby + "',\n";
                }
                quickAjaxArgsText += "    'order' => '" + inputData.qapl_select_order + "',\n";
                if (inputData.qapl_select_post_not_in !== "") {
                    var excludedPostIds = self.getExcludedPostIds(inputData.qapl_select_post_not_in);
                    quickAjaxArgsText += "    'post__not_in' => array(" + excludedPostIds + "),\n";
                }
                if (inputData.qapl_ignore_sticky_posts === 1) {
                    quickAjaxArgsText += "    'ignore_sticky_posts' => " + inputData.qapl_ignore_sticky_posts + ",\n";
                }
                quickAjaxArgsText += ");";
                if (typeof qapl_quick_ajax_helper !== "undefined" && qapl_quick_ajax_helper) {
                    var quickAjaxAttributes = {};
                    quickAjaxAttributes[qapl_quick_ajax_helper.quick_ajax_id] = self.generateId(inputDataString);
                    if (inputData.qapl_layout_quick_ajax_css_style === 1) {
                        quickAjaxAttributes[qapl_quick_ajax_helper.quick_ajax_css_style] = inputData.qapl_layout_quick_ajax_css_style;
                        quickAjaxAttributes[qapl_quick_ajax_helper.grid_num_columns] = inputData.qapl_layout_select_columns_qty;
                    }
                    if (inputData.qapl_layout_quick_ajax_post_item_template) {
                        var clearContainerClass = inputData.qapl_layout_quick_ajax_post_item_template;
                        quickAjaxAttributes[qapl_quick_ajax_helper.post_item_template] = clearContainerClass;
                    }
                    if (inputData.qapl_layout_add_taxonomy_filter_class && inputData.qapl_layout_add_taxonomy_filter_class !== "") {
                        var clearContainerClass = self.cleanClassNames(inputData.qapl_layout_add_taxonomy_filter_class);
                        quickAjaxAttributes[qapl_quick_ajax_helper.taxonomy_filter_class] = clearContainerClass;
                    }
                    if (inputData.qapl_layout_add_container_class && inputData.qapl_layout_add_container_class !== "") {
                        var clearContainerClass = self.cleanClassNames(inputData.qapl_layout_add_container_class);
                        quickAjaxAttributes[qapl_quick_ajax_helper.container_class] = clearContainerClass;
                    }
                    if (inputData.qapl_show_custom_load_more_post_quantity === 1) {
                        quickAjaxAttributes[qapl_quick_ajax_helper.load_more_posts] = inputData.qapl_select_custom_load_more_post_quantity;
                    }
                    if (inputData.qapl_override_global_loader_icon === 1) {
                        quickAjaxAttributes[qapl_quick_ajax_helper.loader_icon] = inputData.qapl_loader_icon;
                    }
                    if (inputData.qapl_ajax_on_initial_load === 1) {
                        quickAjaxAttributes[qapl_quick_ajax_helper.ajax_initial_load] = inputData.qapl_ajax_on_initial_load;
                    }
                }
                //quickAjaxAttributes code
                var quickAjaxAttributesText = "";
                if (Object.keys(quickAjaxAttributes).length > 0) {
                    quickAjaxAttributesText = "";
                    quickAjaxAttributesText += "$quick_ajax_attributes = array(\n";
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
                    quickAjaxAttributesText += ");";
                }
                /////////
                //quickAjaxSortControl code
                var quickAjaxSortControl = null;
                var quickAjaxSortControlValueOptions = null;
                if (inputData.qapl_show_order_button === 1) {
                    var quickAjaxSortControl = inputData.qapl_select_orderby_button_options;
                    if (quickAjaxSortControl && quickAjaxSortControl.length > 0) {
                        var quickAjaxSortControlValueOptions = "$quick_ajax_sort_options = array(";
                        quickAjaxSortControlValueOptions += quickAjaxSortControl.map((option) => `'${option}'`).join(", ");
                        quickAjaxSortControlValueOptions += ");";
                    }
                }
                var quickAjaxSortControlValue = "";
                var quickAjaxSortControlText = "";
                if (quickAjaxSortControl !== null) {
                    quickAjaxSortControlValue = "";
                    quickAjaxSortControlValue += quickAjaxSortControlValueOptions;
                    //qapl_render_taxonomy_filter
                    quickAjaxSortControlText = "";
                    quickAjaxSortControlText += "if(function_exists('qapl_render_sort_controls')):\n";
                    quickAjaxSortControlText += "    qapl_render_sort_controls(\n";
                    quickAjaxSortControlText += "        $quick_ajax_args,\n";
                    quickAjaxSortControlText += "        $quick_ajax_attributes,\n";
                    quickAjaxSortControlText += "        $quick_ajax_sort_options,\n";
                    //remove last comma
                    quickAjaxSortControlText = quickAjaxSortControlText.slice(0, -2) + "\n";
                    quickAjaxSortControlText += "    );\n";
                    quickAjaxSortControlText += "endif;";
                }
                //////
                //quickAjaxTaxonomy code
                var quickAjaxTaxonomy = null;
                if (inputData.qapl_show_select_taxonomy === 1) {
                    var quickAjaxTaxonomy = inputData.qapl_select_taxonomy;
                }
                var quickAjaxTaxonomyFilterValue = "";
                var quickAjaxTaxonomyFilterText = "";
                if (quickAjaxTaxonomy !== null) {
                    quickAjaxTaxonomyFilterValue = "";
                    quickAjaxTaxonomyFilterValue += `$quick_ajax_taxonomy = '${quickAjaxTaxonomy}';`;
                    //qapl_render_taxonomy_filter
                    quickAjaxTaxonomyFilterText = "";
                    quickAjaxTaxonomyFilterText += "if(function_exists('qapl_render_taxonomy_filter')):\n";
                    quickAjaxTaxonomyFilterText += "    qapl_render_taxonomy_filter(\n";
                    quickAjaxTaxonomyFilterText += "        $quick_ajax_args,\n";
                    quickAjaxTaxonomyFilterText += "        $quick_ajax_attributes,\n";
                    quickAjaxTaxonomyFilterText += "        $quick_ajax_taxonomy,\n";
                    //remove last comma
                    quickAjaxTaxonomyFilterText = quickAjaxTaxonomyFilterText.slice(0, -2) + "\n";
                    quickAjaxTaxonomyFilterText += "    );\n";
                    quickAjaxTaxonomyFilterText += "endif;";
                }
                //qapl_render_post_container
                var quick_ajax_post_containerText = "";
                quick_ajax_post_containerText += "if(function_exists('qapl_render_post_container')):\n";
                quick_ajax_post_containerText += "   qapl_render_post_container(\n";
                quick_ajax_post_containerText += "       $quick_ajax_args,\n";
                if (quickAjaxAttributesText !== "") {
                    quick_ajax_post_containerText += "       $quick_ajax_attributes,\n";
                }
                //remove last comma
                quick_ajax_post_containerText = quick_ajax_post_containerText.slice(0, -2) + "\n";
                quick_ajax_post_containerText += "   );\n";
                quick_ajax_post_containerText += "endif;";

                var formattedText = "";
                if (quickAjaxArgsText.trim() !== "") {
                    formattedText += "\n// Define AJAX query parameters for '" + inputData.qapl_select_post_type + "' type posts.\n";
                    formattedText += quickAjaxArgsText.trim() + "\n";
                }
                if (quickAjaxAttributesText.trim() !== "") {
                    formattedText += "\n// Define attributes for AJAX.\n";
                    formattedText += quickAjaxAttributesText.trim() + "\n";
                }
                if (quickAjaxSortControlValue.trim() !== "") {
                    formattedText += "\n// Set the sort options for the button.\n";
                    formattedText += quickAjaxSortControlValue.trim() + "\n";
                }
                if (quickAjaxSortControlText.trim() !== "") {
                    formattedText += "\n// Render the sorting control button.\n";
                    formattedText += quickAjaxSortControlText.trim() + "\n";
                }
                if (quickAjaxTaxonomyFilterValue.trim() !== "") {
                    formattedText += "\n// Set the taxonomy for filtering posts.\n";
                    formattedText += quickAjaxTaxonomyFilterValue.trim() + "\n";
                }
                if (quickAjaxTaxonomyFilterText.trim() !== "") {
                    formattedText += "\n// Render the navigation for '" + inputData.qapl_select_taxonomy + "' taxonomy.\n";
                    formattedText += quickAjaxTaxonomyFilterText.trim() + "\n";
                }
                if (quick_ajax_post_containerText.trim() !== "") {
                    formattedText += "\n// Render the grid for '" + inputData.qapl_select_post_type + "' type posts.\n";
                    formattedText += quick_ajax_post_containerText.trim() + "\n";
                }
                var targetDiv = $("#" + outputDiv);
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
            var self = this;
            $(".quick-ajax-shortcode").on("click", function () {
                self.quick_ajax_select_text(this);
            });
        },
        click_and_select_all: function () {
            var self = this;
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
