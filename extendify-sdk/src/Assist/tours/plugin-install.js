import { __ } from '@wordpress/i18n'

export default {
    id: 'plugin-install-tour',
    settings: {
        allowOverflow: false,
        startFrom: window.extAssistData.adminUrl + 'plugin-install.php',
    },
    steps: [
        {
            title: __('Add New plugin menu', 'extendify'),
            text: __('Click here to access the Add Plugins page.', 'extendify'),
            attachTo: {
                element:
                    '#menu-plugins .wp-submenu a[href="plugin-install.php"]',
                offset: {
                    marginTop: 0,
                    marginLeft: 15,
                },
                position: {
                    x: 'right',
                    y: 'top',
                },
                hook: 'top left',
            },
            events: {},
        },
        {
            title: __('Search', 'extendify'),
            text: __(
                'Search for a plugin by name or functionality.',
                'extendify',
            ),
            attachTo: {
                element: '.search-form.search-plugins',
                offset: {
                    marginTop: 5,
                    marginLeft: -15,
                },
                boxPadding: {
                    top: -5,
                    bottom: 3,
                    left: 5,
                    right: 5,
                },
                position: {
                    x: 'left',
                    y: 'top',
                },
                hook: 'top right',
            },
            events: {},
        },
        {
            title: __('Plugin details', 'extendify'),
            text: __(
                'See important information about each plugin.',
                'extendify',
            ),
            attachTo: {
                element:
                    '#the-list .plugin-card:first-child .plugin-card-bottom',
                offset: {
                    marginTop: 0,
                    marginLeft: 15,
                },
                position: {
                    x: 'right',
                    y: 'bottom',
                },
                hook: 'bottom left',
            },
            events: {},
        },
        {
            title: __('Install now', 'extendify'),
            text: __(
                'Install the plugin. Then, press this button again to activate the plugin.',
                'extendify',
            ),
            attachTo: {
                element: '#the-list .plugin-card:first-child .install-now',
                offset: {
                    marginTop: -5,
                    marginLeft: 15,
                },
                boxPadding: {
                    top: 5,
                    bottom: 5,
                    left: 5,
                    right: 5,
                },
                position: {
                    x: 'right',
                    y: 'top',
                },
                hook: 'top left',
            },
            events: {},
        },
        {
            title: __('Upload Plugin', 'extendify'),
            text: __(
                'If you have a plugin from an external source, you can upload it directly here.',
                'extendify',
            ),
            attachTo: {
                element: '.upload-view-toggle',
                offset: {
                    marginTop: -5,
                    marginLeft: 15,
                },
                boxPadding: {
                    top: 5,
                    bottom: 5,
                    left: 5,
                    right: 5,
                },
                position: {
                    x: 'right',
                    y: 'top',
                },
                hook: 'top left',
            },
            events: {},
        },
    ],
}
