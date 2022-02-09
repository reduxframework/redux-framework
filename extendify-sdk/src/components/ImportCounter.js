import classnames from 'classnames'
import { Icon } from '@wordpress/icons'
import { __, _n, sprintf } from '@wordpress/i18n'
import { useEffect, memo, useRef } from '@wordpress/element'
import { alert, download } from './icons/'
import { useUserStore } from '../state/User'
import { User as UserApi } from '../api/User'
import { growthArrow } from './icons'
import { safeHTML } from '@wordpress/dom'

export const ImportCounter = memo(function ImportCounter() {
    const remainingImports = useUserStore((state) => state.remainingImports)
    const allowedImports = useUserStore((state) => state.allowedImports)
    const count = remainingImports()
    const status = count > 0 ? 'has-imports' : 'no-imports'
    const backgroundColor =
        status === 'has-imports'
            ? 'bg-extendify-main hover:bg-extendify-main-dark'
            : 'bg-extendify-alert'
    const icon = status === 'has-imports' ? download : alert
    const buttonRef = useRef()

    useEffect(() => {
        if (allowedImports < 1 || !allowedImports) {
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
        // tabIndex for group focus animations
        <div tabIndex="0" className="group relative">
            <a
                target="_blank"
                ref={buttonRef}
                rel="noreferrer"
                className={classnames(
                    backgroundColor,
                    'hidden sm:flex w-full no-underline button-focus text-sm justify-between py-3 px-4 text-white rounded',
                )}
                href={`https://www.extendify.com/pricing/?utm_source=${encodeURIComponent(
                    window.extendifyData.sdk_partner,
                )}&utm_medium=library&utm_campaign=import-counter&utm_content=get-more&utm_term=${status}`}>
                <span className="flex items-center space-x-2 no-underline text-xs">
                    <Icon icon={icon} size={14} />
                    <span>
                        {sprintf(
                            _n('%s Import', '%s Imports', count, 'extendify'),
                            count,
                        )}
                    </span>
                </span>
                <span className="text-white text-sm no-underline font-medium outline-none flex items-center">
                    {__('Get more', 'extendify')}
                    <Icon icon={growthArrow} size={24} className="-mr-1.5" />
                </span>
            </a>
            <div
                className="invisible opacity-0 -translate-y-full absolute duration-300 delay-200 ease-in-out group-hover:-top-2.5 group-hover:opacity-100 group-hover:visible group-focus:-top-2.5 group-focus:opacity-100 group-focus:visible top-0 transform transition-all w-full extendify-bottom-arrow shadow-md"
                tabIndex="-1">
                <a
                    href={`https://www.extendify.com/pricing/?utm_source=${encodeURIComponent(
                        window.extendifyData.sdk_partner,
                    )}&utm_medium=library&utm_campaign=import-counter-tooltip&utm_content=get-50-off&utm_term=${status}`}
                    className="block bg-gray-900 text-white p-4 no-underline rounded bg-cover"
                    style={{
                        backgroundImage: `url(${window.extendifyData.asset_path}/logo-tips.png)`,
                    }}>
                    <span
                        dangerouslySetInnerHTML={{
                            __html: safeHTML(
                                sprintf(
                                    __(
                                        '%1$sGet %2$s off%3$s Extendify Pro when you upgrade today!',
                                        'extendify',
                                    ),
                                    '<strong>',
                                    '50%',
                                    '</strong>',
                                ),
                            ),
                        }}
                    />
                </a>
            </div>
        </div>
    )
})
