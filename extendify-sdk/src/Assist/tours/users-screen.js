import { __ } from '@wordpress/i18n'

export default {
    id: 'users-screen-tour',
    settings: {
        allowOverflow: true,
        startFrom: window.extAssistData.adminUrl + 'users.php',
    },
    steps: [
        {
            title: __('All Users menu', 'extendify'),
            text: __(
                'Click here to view and manage the users on your site.',
                'extendify',
            ),
            attachTo: {
                element: '#menu-users ul > li:nth-child(2)',
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
            title: __('Users', 'extendify'),
            text: __(
                'See all of your users, including admin users in this table.',
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
            title: __('Edit user information', 'extendify'),
            text: __(
                "Click the edit button to change the user's role, manage their account, or change their profile information.",
                'extendify',
            ),
            attachTo: {
                element: 'tbody#the-list > tr:nth-child(1) > td.username',
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
            events: {
                onAttach: () => {
                    if (window.innerWidth <= 960) return
                    document.querySelector(
                        'tbody#the-list > tr:nth-child(1) > td.username .row-actions',
                    ).style.left = '0'
                },
                onDetach: () => {
                    if (window.innerWidth <= 960) return
                    document.querySelector(
                        'tbody#the-list > tr:nth-child(1) > td.username .row-actions',
                    ).style.left = '-9999em'
                },
            },
        },
        {
            title: __('Search for users', 'extendify'),
            text: __(
                'Use the search bar to find a particular user.',
                'extendify',
            ),
            attachTo: {
                element: 'p.search-box',
                offset: {
                    marginTop: -5,
                    marginLeft: -15,
                },
                boxPadding: {
                    top: 5,
                    bottom: 5,
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
            title: __('Add a new user', 'extendify'),
            text: __(
                'Click the Add New button to add a new user to your site.',
                'extendify',
            ),
            attachTo: {
                element: '.page-title-action',
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
