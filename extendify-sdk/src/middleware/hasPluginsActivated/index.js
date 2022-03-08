import { render } from '@wordpress/element'
import { checkIfUserNeedsToActivatePlugins } from '../helpers'
import ActivatePluginsModal from './ActivatePluginsModal'

export const hasPluginsActivated = async (template) => {
    return {
        id: 'hasPluginsActivated',
        pass: !(await checkIfUserNeedsToActivatePlugins(template)),
        allow() {},
        deny() {
            return new Promise(() => {
                render(
                    <ActivatePluginsModal showClose={true} />,
                    document.getElementById('extendify-root'),
                )
            })
        },
    }
}
