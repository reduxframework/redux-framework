import { checkIfUserNeedsToInstallPlugins } from '../helpers'
import RequiredPluginsModal from './RequiredPluginsModal'
import { render } from '@wordpress/element'

export const hasRequiredPlugins = async (template) => {
    return {
        id: 'hasRequiredPlugins',
        pass: !(await checkIfUserNeedsToInstallPlugins(template)),
        allow() {},
        deny() {
            return new Promise(() => {
                render(<RequiredPluginsModal/>, document.getElementById('extendify-root'))
            })
        },
    }
}
