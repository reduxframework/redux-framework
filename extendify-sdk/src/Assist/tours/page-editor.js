import { createBlock } from '@wordpress/blocks'
import { __ } from '@wordpress/i18n'
import { waitUntilExists, waitUntilGone } from '@assist/util/element'

const hasIframe = () => !!document.querySelector('iframe[name="editor-canvas"]')
const disableKeyboard = (e) => e.preventDefault()

export default {
    id: 'page-editor-tour',
    settings: {
        allowOverflow: true,
        startFrom:
            window.extAssistData.adminUrl + 'post-new.php?post_type=page',
    },
    onStart: async () => {
        // close sidebar if open
        document
            .querySelector(`[aria-label="${__('Settings')}"].is-pressed`)
            ?.click()
        // If the Extendify library is open, close it
        return await dispatchEvent(new CustomEvent('extendify::close-library'))
    },
    steps: [
        {
            title: __('Add a Block', 'extendify'),
            text: __('Click the plus to open the block inserter.', 'extendify'),
            attachTo: {
                element: '.edit-post-header-toolbar__inserter-toggle',
                offset: {
                    marginTop: 15,
                    marginLeft: 0,
                },
                position: {
                    x: 'left',
                    y: 'bottom',
                },
                hook: 'top left',
            },
            events: {},
        },
        {
            title: __('Block Inserter', 'extendify'),
            text: __(
                'Add a block by clicking or dragging it onto the page.',
                'extendify',
            ),
            attachTo: {
                element: '.block-editor-inserter__menu',
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
            options: {
                blockPointerEvents: true,
            },
            events: {
                beforeAttach: async () => {
                    if (window.innerWidth <= 960) return
                    document
                        .querySelector(
                            '.edit-post-header-toolbar__inserter-toggle:not(.is-pressed)',
                        )
                        ?.click()
                    return await waitUntilExists('.block-editor-inserter__tabs')
                },
                onAttach: () => {
                    if (window.innerWidth <= 960) return
                    const toggle = document.querySelector(
                        '.edit-post-header-toolbar__inserter-toggle',
                    )
                    onMutate.observe(toggle, { attributes: true })
                    window.addEventListener('keydown', disableKeyboard)
                },
                onDetach: async () => {
                    if (window.innerWidth <= 960) return
                    onMutate.disconnect()
                    window.removeEventListener('keydown', disableKeyboard)
                    document
                        .querySelector(
                            '.edit-post-header-toolbar__inserter-toggle.is-pressed',
                        )
                        ?.click()
                    console.log('here')
                    await waitUntilGone('.block-editor-inserter__block-list')
                    console.log(
                        document.querySelector(
                            '.block-editor-inserter__block-list',
                        ),
                    )
                    requestAnimationFrame(() => {
                        document
                            .getElementById('assist-tour-next-button')
                            ?.focus()
                    })
                },
            },
        },
        {
            title: __('Page Title', 'extendify'),
            text: __(
                'Edit the page title by clicking it. Note: The title may or may not show up on the published page, depending on the page template used.',
                'extendify',
            ),
            attachTo: {
                element: () =>
                    hasIframe()
                        ? 'iframe[name="editor-canvas"]'
                        : '.wp-block-post-title',
                offset: () => ({
                    marginTop: hasIframe() ? 15 : 0,
                    marginLeft: hasIframe() ? -15 : 15,
                }),
                position: {
                    x: 'right',
                    y: 'top',
                },
                hook: () => (hasIframe() ? 'top right' : 'top left'),
            },
            events: {
                beforeAttach: async () => {
                    if (window.innerWidth <= 960) return
                    await window.wp.data
                        .dispatch('core/editor')
                        .editPost({ title: 'Sample Post' })
                    return
                },
            },
        },
        {
            title: __('Blocks', 'extendify'),
            text: __(
                'Each block will show up on the page and can be edited by clicking on it.',
                'extendify',
            ),
            attachTo: {
                element: () =>
                    hasIframe()
                        ? 'iframe[name="editor-canvas"]'
                        : '.wp-block-post-content > p',
                offset: () => ({
                    marginTop: hasIframe() ? 15 : 0,
                    marginLeft: hasIframe() ? -15 : 15,
                }),
                position: {
                    x: 'right',
                    y: 'top',
                },
                hook: () => (hasIframe() ? 'top right' : 'top left'),
            },
            events: {
                beforeAttach: async () => {
                    if (window.innerWidth <= 960) return
                    // get block count
                    const blockCount = await window.wp.data
                        .select('core/block-editor')
                        .getBlockCount()
                    if (blockCount > 0) return
                    // create a block and insert it
                    const block = createBlock('core/paragraph', {
                        content: __(
                            "This is a sample paragraph block. It can be several sentences long and will span multiple rows. You can add as many blocks as you'd like to the page.",
                            'extendify',
                        ),
                    })
                    await window.wp.data
                        .dispatch('core/block-editor')
                        .insertBlock(block)
                    return hasIframe()
                        ? await window.wp.data
                              .dispatch('core/block-editor')
                              .flashBlock(block.clientId)
                        : null
                },
            },
        },
        {
            title: __('Page and Block Settings', 'extendify'),
            text: __(
                'Select either page or block to change the settings for the entire page or the block that is selected.',
                'extendify',
            ),
            attachTo: {
                element: `.interface-interface-skeleton__sidebar[aria-label="${__(
                    'Editor settings',
                )}"]`,
                offset: {
                    marginTop: 0,
                    marginLeft: -15,
                },
                position: {
                    x: 'left',
                    y: 'top',
                },
                hook: 'top right',
            },
            events: {
                beforeAttach: async () => {
                    if (window.innerWidth <= 960) return
                    document
                        .querySelector(
                            `[aria-label="${__('Settings')}"]:not(.is-pressed)`,
                        )
                        ?.click()
                    await waitUntilExists(
                        `.interface-interface-skeleton__sidebar[aria-label="${__(
                            'Editor settings',
                        )}"]`,
                    )
                    document
                        .querySelector(
                            `.edit-post-sidebar__panel-tab[data-label="${__(
                                'Page',
                            )}"]`,
                        )
                        ?.click()
                    await waitUntilExists('.edit-post-post-status')
                    document
                        .querySelector(
                            '.edit-post-post-status:not(.is-opened) button',
                        )
                        ?.click()
                    await waitUntilExists('.edit-post-post-status.is-opened')
                    return
                },
            },
        },
        {
            title: __('Preview', 'extendify'),
            text: __(
                'Click preview to view how your changes look on the front end of your site.',
                'extendify',
            ),
            attachTo: {
                element: '.block-editor-post-preview__button-toggle',
                offset: {
                    marginTop: 0,
                    marginLeft: -15,
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
            title: __('Publish or Save', 'extendify'),
            text: __(
                'Click publish or update to save the changes you’ve made to the page and make them live on the site.',
                'extendify',
            ),
            attachTo: {
                element: '.editor-post-publish-button__button',
                offset: {
                    marginTop: 15,
                },
                position: {
                    x: 'right',
                    y: 'bottom',
                },
                hook: 'top right',
            },
            events: {},
        },
    ],
}

const onMutate = new MutationObserver(() => {
    document
        .querySelector(
            '.edit-post-header-toolbar__inserter-toggle:not(.is-pressed)',
        )
        ?.click()
})
