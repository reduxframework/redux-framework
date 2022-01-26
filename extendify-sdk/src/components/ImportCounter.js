import classnames from 'classnames'
import { Icon } from '@wordpress/icons'
import { __, _n, sprintf } from '@wordpress/i18n'
import { useEffect } from '@wordpress/element'
import { alert, download } from './icons/'
import { useUserStore } from '../state/User'
import { User as UserApi } from '../api/User'
import { growthArrow } from './icons'

export const ImportCounter = () => {
    const remainingImports = useUserStore((state) => state.remainingImports)
    const allowedImports = useUserStore((state) => state.allowedImports)
    const count = remainingImports()
    const status = count > 0 ? 'has-imports' : 'no-imports'
    const backgroundColor =
        status === 'has-imports'
            ? 'bg-extendify-main hover:bg-extendify-main-dark'
            : 'bg-extendify-alert'
    const icon = status === 'has-imports' ? download : alert

    useEffect(() => {
        if (!allowedImports) {
            const fallback = 5
            UserApi.allowedImports()
                .then((allowedImports) => {
                    allowedImports = /^[1-9]\d*$/.test(allowedImports)
                        ? allowedImports
                        : fallback
                    useUserStore.setState({ allowedImports })
                })
                .catch(() =>
                    useUserStore.setState({ allowedImports: fallback }),
                )
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
            )}&utm_medium=library&utm_campaign=import-counter&utm_content=get-more&utm_term=${status}`}>
            <div className="flex items-center space-x-2 no-underline text-xs">
                <Icon icon={icon} size={14} />
                <span>
                    {sprintf(
                        _n('%s Import', '%s Imports', count, 'extendify'),
                        count,
                    )}
                </span>
            </div>
            <span className="text-white text-sm no-underline font-medium outline-none flex items-center">
                {__('Get more', 'extendify')}
                <Icon icon={growthArrow} size={24} className="-mr-1.5" />
            </span>
        </a>
    )
}
