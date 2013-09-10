/* global redux_change */

jQuery.noConflict();
/** Fire up jQuery - let's dance!
 */
jQuery(document).ready(function($) {
	
	jQuery(".redux-spacing-top, .redux-spacing-bottom, .redux-spacing-left, .redux-spacing-right").numeric({
	});
	jQuery(".redux-spacing-units").select2({
		width: 'resolve',
		triggerChange: true,
		allowClear: true
	});
});