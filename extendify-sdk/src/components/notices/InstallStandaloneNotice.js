import { __ } from '@wordpress/i18n'
import { Button } from '@wordpress/components'
import { useState } from '@wordpress/element'
import { Plugins } from '../../api/Plugins'
import classNames from 'classnames'
import { General } from '../../api/General'
import { useUserStore } from '../../state/User'

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
                {__(
                    'Install the new Extendify Library plugin to get the latest we have to offer',
                    'extendify',
                )}
            </span>
            <span className="px-2 opacity-50" aria-hidden="true">
                &#124;
            </span>
            <div className="inline-flex space-x-2 items-center relative">
                <Button
                    variant="link"
                    className={classNames(
                        'text-black underline hover:no-underline p-0 h-auto',
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
                        className="text-black underline hover:no-underline p-0 h-auto absolute left-0 opacity-100"
                        onClick={() => {}}>
                        {text}
                    </Button>
                ) : null}
            </div>
        </div>
    )
}
