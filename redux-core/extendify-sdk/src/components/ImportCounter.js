/**
 * External dependencies
 */
import classnames from 'classnames'

/**
 * WordPress dependencies
 */
import { Icon } from '@wordpress/icons'
import { __, sprintf } from '@wordpress/i18n'

/**
 * Internal dependencies
 */
import { alert } from './icons/'
import { download } from './icons/'
import { useUserStore } from '../state/User'

function ImportCounter() {
    const remainingImports = useUserStore(state => state.remainingImports)
    const allowedImports = useUserStore(state => state.allowedImports)
    const status = remainingImports() > 0 ? 'has-imports' : 'no-imports'
    const backgroundColor = status === 'has-imports' ? 'bg-extendify-main hover:bg-extendify-main-dark' : 'bg-extendify-alert'
    const icon = status === 'has-imports' ? download : alert

    return <a
        target="_blank"
        rel="noreferrer"
        className={ classnames(backgroundColor, 'flex w-full no-underline button-focus -mt-10 text-sm justify-between py-3 px-4 text-white rounded')}
        href={`https://www.extendify.com/pricing/?utm_source=${encodeURIComponent(window.extendifySdkData.sdk_partner)}&utm_medium=library&utm_campaign=import-counter&utm_content=upgrade&utm_term=${status}`}>
        <div className='flex items-center space-x-2 no-underline'>
            <Icon icon={ icon } size={ 14 } />
            <span>
                { sprintf(
                    __('%s/%s Imports', 'extendify-sdk'), remainingImports(), Number(allowedImports),
                ) }
            </span>
        </div>
        <span className="text-white no-underline font-medium outline-none">
            { __('Upgrade', 'extendify-sdk') }
        </span>
    </a>
}

export default ImportCounter
