import { dispatch } from '@wordpress/data'
import { __ } from '@wordpress/i18n'
import { Templates } from '@library/api/Templates'
import { useUserStore } from '@library/state/User'

// This fires after a template is inserted
export const templateHandler = {
    register() {
        const { createNotice } = dispatch('core/notices')
        const increaseImports = useUserStore.getState().incrementImports
        window.addEventListener('extendify::template-inserted', (event) => {
            createNotice('info', __('Page layout added', 'extendify'), {
                isDismissible: true,
                type: 'snackbar',
            })
            // This is put off to the stack in attempt to fix a bug where
            // some users are having their imports go from 3->0 in an instant
            setTimeout(() => {
                increaseImports()
                Templates.import(event.detail?.template)
            }, 0)
        })
    },
}
