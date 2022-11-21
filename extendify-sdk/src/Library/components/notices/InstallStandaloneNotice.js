import { Button } from '@wordpress/components'
import { useState } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import classNames from 'classnames'
import { General } from '@library/api/General'
import { Plugins } from '@library/api/Plugins'
import { useUserStore } from '@library/state/User'

export const InstallStandaloneNotice = () => {
    const [text, setText] = useState('')
    const giveFreebieImports = useUserStore((state) => state.giveFreebieImports)
    const installAndActivate = () => {
        setText(__('Installing...', 'extendify'))
        Promise.all([
            General.ping('stln-footer-install'),
            Plugins.installAndActivate(['extendify']),
            new Promise((resolve) => setTimeout(resolve, 1000)),
        ])
            .then(async () => {
                giveFreebieImports(10)
                setText(__('Success! Reloading...', 'extendify'))
                await General.ping('stln-footer-success')
                window.location.reload()
            })
            .catch(async (error) => {
                console.error(error)
                setText(__('Error. See console.', 'extendify'))
                await General.ping('stln-footer-fail')
            })
    }

    return (
        <div>
            <span className="text-black">
                {sprintf(
                    // translators: %s: Extendify Library term.
                    __(
                        'Install the new %s plugin to get the latest we have to offer',
                        'extendify',
                    ),
                    'Extendify Library',
                )}
            </span>
            <span className="px-2 opacity-50" aria-hidden="true">
                &#124;
            </span>
            <div className="relative inline-flex items-center space-x-2">
                <Button
                    variant="link"
                    className={classNames(
                        'h-auto p-0 text-black underline hover:no-underline',
                        { 'opacity-0': text },
                    )}
                    onClick={installAndActivate}>
                    {__('Install Extendify standalone plugin', 'extendify')}
                </Button>
                {/* Little hacky to keep the text in place. Might need to tweak this */}
                {text ? (
                    <Button
                        variant="link"
                        disabled={true}
                        className="absolute left-0 h-auto p-0 text-black underline opacity-100 hover:no-underline"
                        onClick={() => {}}>
                        {text}
                    </Button>
                ) : null}
            </div>
        </div>
    )
}
