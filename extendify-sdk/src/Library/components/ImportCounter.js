import { safeHTML } from '@wordpress/dom'
import { useEffect, memo, useRef } from '@wordpress/element'
import { __, _n, sprintf } from '@wordpress/i18n'
import { Icon } from '@wordpress/icons'
import clasNames from 'classnames'
import { General } from '@library/api/General'
import { User as UserApi } from '@library/api/User'
import { useUserStore } from '@library/state/User'
import { growthArrow } from './icons'
import { alert, download } from './icons/'

export const ImportCounter = memo(function ImportCounter() {
    const remainingImports = useUserStore((state) => state.remainingImports)
    const allowedImports = useUserStore((state) => state.allowedImports)
    const count = remainingImports()
    const status = count > 0 ? 'has-imports' : 'no-imports'
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
        <div tabIndex="0" className="group relative mb-5">
            <a
                target="_blank"
                ref={buttonRef}
                rel="noreferrer"
                className={clasNames(
                    'button-focus hidden w-full justify-between rounded py-3 px-4 text-sm text-white no-underline sm:flex',
                    {
                        'bg-wp-theme-500 hover:bg-wp-theme-600': count > 0,
                        'bg-extendify-alert': !count,
                    },
                )}
                onClick={async () => await General.ping('import-counter-click')}
                href={`https://www.extendify.com/pricing/?utm_source=${encodeURIComponent(
                    window.extendifyData.sdk_partner,
                )}&utm_medium=library&utm_campaign=import-counter&utm_content=get-more&utm_term=${status}`}>
                <span className="flex items-center space-x-2 text-xs no-underline">
                    <Icon icon={count > 0 ? download : alert} size={14} />
                    <span>
                        {sprintf(
                            // translators: %s is the number of imports remaining
                            _n('%s Import', '%s Imports', count, 'extendify'),
                            count,
                        )}
                    </span>
                </span>
                <span className="outline-none flex items-center text-sm font-medium text-white no-underline">
                    {__('Get more', 'extendify')}
                    <Icon icon={growthArrow} size={24} className="-mr-1.5" />
                </span>
            </a>
            <div
                className="extendify-bottom-arrow invisible absolute top-0 w-full -translate-y-full transform opacity-0 shadow-md transition-all delay-200 duration-300 ease-in-out group-hover:visible group-hover:-top-2.5 group-hover:opacity-100 group-focus:visible group-focus:-top-2.5 group-focus:opacity-100"
                tabIndex="-1">
                <a
                    href={`https://www.extendify.com/pricing/?utm_source=${encodeURIComponent(
                        window.extendifyData.sdk_partner,
                    )}&utm_medium=library&utm_campaign=import-counter-tooltip&utm_content=get-50-off&utm_term=${status}`}
                    className="block bg-gray-900 text-white p-4 no-underline rounded bg-cover"
                    onClick={async () =>
                        await General.ping('import-counter-tooltip-click')
                    }
                    style={{
                        backgroundImage: `url(${window.extendifyData.asset_path}/logo-tips.png)`,
                        backgroundSize: '100% 100%',
                    }}>
                    <span
                        dangerouslySetInnerHTML={{
                            __html: safeHTML(
                                sprintf(
                                    __(
                                        // translators: %s is a discount amount
                                        '%1$sGet %2$s off%3$s %4$s Pro when you upgrade today!',
                                        'extendify',
                                    ),
                                    '<strong>',
                                    '50%',
                                    '</strong>',
                                    'Extendify',
                                ),
                            ),
                        }}
                    />
                </a>
            </div>
        </div>
    )
})
