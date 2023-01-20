import { __ } from '@wordpress/i18n'
import {
    adminBgColor,
    adminTextColor,
    assistAdminBarBgColor,
} from '../util/colors'

export default {
    id: 'welcome-tour',
    title: __('Welcome Tour', 'extendify'),
    description: __('The Welcome Tour', 'extendify'),
    settings: {
        allowOverflow: true,
        startFrom:
            window.extAssistData.adminUrl +
            'admin.php?page=extendify-assist#dashboard',
    },
    steps: [
        {
            title: __('Pages', 'extendify'),
            text: __(
                'Use the pages menu to add, delete, or edit the pages on your site.',
                'extendify',
            ),
            image: 'https://assets.extendify.com/tours/welcome/add-pages.gif',
            attachTo: {
                element: '#menu-pages',
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
                onAttach: (el) => {
                    el.style.setProperty('background-color', adminBgColor())
                    Array.from(el.querySelectorAll('*')).forEach((child) => {
                        child.style.setProperty('color', adminTextColor())
                    })
                },
            },
        },
        {
            title: __('Blog Posts', 'extendify'),
            text: __(
                'Use the posts menu to add, delete, or edit blog posts on your site.',
                'extendify',
            ),
            image: 'https://assets.extendify.com/tours/welcome/blog-posts.gif',
            attachTo: {
                element: '#menu-posts',
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
                onAttach: (el) => {
                    el.style.setProperty('background-color', adminBgColor())
                    Array.from(el.querySelectorAll('*')).forEach((child) => {
                        child.style.setProperty('color', adminTextColor())
                    })
                },
            },
        },
        {
            title: __('View Site', 'extendify'),
            text: __(
                "At any time, you can view your site (from a visitor's perspective) from the top admin bar under your site's name.",
                'extendify',
            ),
            image: 'https://assets.extendify.com/tours/welcome/view-site.gif',
            attachTo: {
                element: '#wp-admin-bar-view-site',
                offset: {
                    marginTop: 0,
                    marginLeft: 10,
                },
                position: {
                    x: 'right',
                    y: 'top',
                },
                hook: 'top left',
            },
            cloneOptions: {
                includePsuedoDepth: 2,
            },
            events: {
                beforeAttach: () => {
                    document
                        .querySelector('#wp-admin-bar-site-name')
                        ?.classList?.add('hover')
                },
                onAttach: (el) => {
                    el?.querySelectorAll('#wp-admin-bar-view-site *').forEach(
                        (child) => {
                            child?.style?.setProperty(
                                'background-color',
                                assistAdminBarBgColor(),
                                'important',
                            )
                            child?.style?.setProperty(
                                'color',
                                adminTextColor(),
                                'important',
                            )
                        },
                    )
                },
                onDetach: () => {
                    document
                        .querySelector('#wp-admin-bar-site-name')
                        ?.classList?.remove('hover')
                },
            },
        },
        {
            title: __('Site Assistant', 'extendify'),
            text: __(
                'Your site assistant will give you personalized recommendations for your site and help guide you through what is needed to achieve your goals. You can access the assistant from the top admin bar or in the left side menu.',
                'extendify',
            ),
            image: 'https://assets.extendify.com/tours/welcome/site-assistant.gif',
            attachTo: {
                element: '#wp-admin-bar-extendify-assist-link',
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
            events: {
                onAttach: (el) => {
                    el.querySelector('.ab-item')?.style?.setProperty(
                        'background-color',
                        assistAdminBarBgColor(),
                        'important',
                    )
                },
            },
        },
    ],
}
