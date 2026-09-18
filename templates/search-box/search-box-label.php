<?php 
if (!defined('ABSPATH')) {
    exit;
}
/* Search Box Name: Search Box (Text Label) */
//QUICK_AJAX_SEARCH_FIELD is replaced with the search input, QUICK_AJAX_LABEL with the button label
//no aria-label here on purpose - the label is the visible button text, so it is already the accessible name
//the qapl-search-box class carries the shared layout, the second class marks this variant ?>
<div class="qapl-search-box qapl-search-box-label">QUICK_AJAX_SEARCH_FIELD<button type="button" class="qapl-search-submit qapl-button">QUICK_AJAX_LABEL</button></div>
