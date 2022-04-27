import { safeHTML } from '@wordpress/dom'
import { useEffect, memo } from '@wordpress/element'
import { __, _n, _x, sprintf } from '@wordpress/i18n'
import { Icon } from '@wordpress/icons'
import classNames from 'classnames'
import { General } from '@extendify/api/General'
import { User as UserApi } from '@extendify/api/User'
import { useUserStore } from '@extendify/state/User'
import { brandMark } from './icons'

export const SidebarNotice = memo(function SidebarNotice() {
    const remainingImports = useUserStore((state) => state.remainingImports)
    const allowedImports = useUserStore((state) => state.allowedImports)
    const count = remainingImports()
    const link = `https://www.extendify.com/pricing/?utm_source=${encodeURIComponent(
        window.extendifyData.sdk_partner,
    )}&utm_medium=library&utm_campaign=import-counter&utm_content=get-more&utm_term=${
        count > 0 ? 'has-imports' : 'no-imports'
    }&utm_group=${useUserStore.getState().activeTestGroupsUtmValue()}`

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
        <a
            target="_blank"
            className="absolute bottom-4 left-0 mx-5 hidden sm:block bg-white rounded border-solid border border-gray-200 p-4 text-left no-underline group button-focus"
            rel="noreferrer"
            onClick={async () => await General.ping('fp-sb-click')}
            href={link}>
            <span className="flex -ml-1.5 space-x-1.5">
                <Icon icon={brandMark} />
                <span className="mb-1 text-gray-800 font-medium text-sm">
                    {__('Free Plan', 'extendify')}
                </span>
            </span>
            <span className="text-gray-700 block ml-6 mb-1.5">
                {sprintf(
                    _n(
                        'You have %s free pattern and layout import remaining this month.',
                        'You have %s free pattern and layout imports remaining this month.',
                        count,
                        'extendify',
                    ),
                    count,
                )}
            </span>
            <span
                className={classNames(
                    'block font-semibold ml-6 text-sm group-hover:underline',
                    {
                        'text-red-500': count < 2,
                        'text-wp-theme-500': count > 1,
                    },
                )}
                dangerouslySetInnerHTML={{
                    __html: safeHTML(
                        sprintf(
                            _x(
                                'Upgrade today %s',
                                'The replacement string is a right arrow and context is not lost if removed.',
                                'extendify',
                            ),
                            `<span class="text-base">
                                ${String.fromCharCode(8250)}
                            </span>`,
                        ),
                    ),
                }}
            />
        </a>
    )
})
