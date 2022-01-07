import classnames from 'classnames'
import { Icon } from '@wordpress/icons'
import { __, sprintf } from '@wordpress/i18n'
import { useEffect } from '@wordpress/element'
import { alert } from './icons/'
import { download } from './icons/'
import { useUserStore } from '../state/User'
import { User as UserApi } from '../api/User'

export const ImportCounter = () => {
    const remainingImports = useUserStore((state) => state.remainingImports)
    const allowedImports = useUserStore((state) => state.allowedImports)
    const status = remainingImports() > 0 ? 'has-imports' : 'no-imports'
    const backgroundColor =
        status === 'has-imports'
            ? 'bg-extendify-main hover:bg-extendify-main-dark'
            : 'bg-extendify-alert'
    const icon = status === 'has-imports' ? download : alert

    useEffect(() => {
        if (!allowedImports) {
            UserApi.allowedImports().finally((allowedImports) => {
                // If something goes wrong and this isn't a number, then default to 5
                allowedImports = /^[1-9]\d*$/.test(allowedImports)
                    ? allowedImports
                    : '5'
                useUserStore.setState({ allowedImports })
            })
        }
    }, [allowedImports])

    if (!allowedImports) {
        return null
    }

    return (
        <a
            target="_blank"
            rel="noreferrer"
            className={classnames(
                backgroundColor,
                'hidden sm:flex w-full no-underline button-focus text-sm justify-between py-3 px-4 text-white rounded',
            )}
            href={`https://www.extendify.com/pricing/?utm_source=${encodeURIComponent(
                window.extendifyData.sdk_partner,
            )}&utm_medium=library&utm_campaign=import-counter&utm_content=upgrade&utm_term=${status}`}>
            <div className="flex items-center space-x-2 no-underline">
                <Icon icon={icon} size={14} />
                <span>
                    {sprintf(
                        __('%s/%s Imports', 'extendify'),
                        remainingImports(),
                        Number(allowedImports),
                    )}
                </span>
            </div>
            <span className="text-white no-underline font-medium outline-none">
                {__('Upgrade', 'extendify')}
            </span>
        </a>
    )
}
