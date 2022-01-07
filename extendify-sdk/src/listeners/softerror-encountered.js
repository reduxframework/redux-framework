import { camelCase } from 'lodash'
import { render } from '@wordpress/element'
import RequiredPluginsModal from '../middleware/hasRequiredPlugins/RequiredPluginsModal'

// use this to trigger an error from outside the application
export const softErrorHandler = {
    register() {
        window.addEventListener('extendify::softerror-encountered', (event) => {
            this[camelCase(event.detail.type)](event.detail)
        })
    },
    versionOutdated(error) {
        render(
            <RequiredPluginsModal
                title={error.data.title}
                requiredPlugins={['extendify']}
                message={error.data.message}
                buttonLabel={error.data.buttonLabel}
                forceOpen={true}
            />,
            document.getElementById('extendify-root'),
        )
    },
}
