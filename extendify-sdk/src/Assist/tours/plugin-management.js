import { __ } from '@wordpress/i18n'

export default {
    id: 'plugin-management-tour',
    settings: {
        allowOverflow: false,
        startFrom: window.extAssistData.adminUrl + 'plugins.php',
    },
    steps: [
        {
            title: __('Installed Plugins menu', 'extendify'),
            text: __(
                'Click this menu to see and manage the plugins you have installed.',
                'extendify',
            ),
            attachTo: {
                element: '#menu-plugins ul > li:nth-child(2)',
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
            title: __('Installed plugins', 'extendify'),
            text: __(
                'See all plugins installed on your site. This includes plugins that are active and deactivated.',
                'extendify',
            ),
            attachTo: {
                element: 'tbody#the-list > tr:nth-child(1)',
                offset: {
                    marginTop: 15,
                    marginLeft: 0,
                },
                position: {
                    x: 'right',
                    y: 'bottom',
                },
                hook: 'top right',
            },
            events: {},
        },
        {
            title: __('Deactivate/activate option', 'extendify'),
            text: __(
                'Under each plugin you can activate or deactivate it.',
                'extendify',
            ),
            attachTo: {
                element: 'tbody#the-list > tr:nth-child(1) > td.plugin-title',
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
        // {
        //     title: __('Enable auto-updates', 'extendify'),
        //     text: __(
        //         "If you'd like, you can set any plugin to auto-update when a new version is available.",
        //         'extendify',
        //     ),
        //     attachTo: {
        //         element:
        //             'tbody#the-list > tr:nth-child(1) > td.column-auto-updates',
        //         offset: {
        //             marginTop: 0,
        //             marginLeft: -15,
        //         },
        //         position: {
        //             x: 'left',
        //             y: 'top',
        //         },
        //         hook: 'top right',
        //     },
        //     events: {},
        // },
        {
            title: __('Add another', 'extendify'),
            text: __(
                'Click here to add another plugin to your site.',
                'extendify',
            ),
            attachTo: {
                element: 'a.page-title-action',
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
