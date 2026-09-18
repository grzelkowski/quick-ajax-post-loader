<?php
if (!defined('ABSPATH')) {
    exit;
}
/* Search Box Name: Search Box (Magnifier Icon) */
//QUICK_AJAX_SEARCH_FIELD is replaced with the search input, QUICK_AJAX_LABEL with the button label
//the qapl-search-box class carries the shared layout, the second class marks this variant
//stroke="currentColor" makes the icon follow the button color, with and without the plugin theme ?>
<div class="qapl-search-box qapl-search-box-icon">QUICK_AJAX_SEARCH_FIELD<button type="button" class="qapl-search-submit" aria-label="QUICK_AJAX_LABEL"><svg class="qapl-search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" width="16" height="16" aria-hidden="true" focusable="false"><g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="6.5" cy="6.5" r="4.5"></circle><path d="M10.5 10.5l4 4"></path></g></svg></button></div>
