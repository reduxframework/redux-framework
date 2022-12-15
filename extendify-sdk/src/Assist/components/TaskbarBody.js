import { useLayoutEffect, useState } from '@wordpress/element'
import classNames from 'classnames'
import { useTaskbarStore } from '@assist/state/Taskbar'

export const TaskbarBody = () => {
    const { open } = useTaskbarStore()
    const [offset, setOffset] = useState(0)

    useLayoutEffect(() => {
        const handle = () => {
            // get the height of the admin bar
            setOffset(document.getElementById('wpadminbar')?.offsetHeight ?? 0)
        }
        handle()
        addEventListener('resize', handle)
        return () => removeEventListener('resize', handle)
    }, [])

    return (
        <>
            <div
                className={classNames(
                    'transition-all transform duration-300 ease-in-out h-32',
                    { '-mt-32': !open },
                )}
            />
            <div
                style={{
                    top: `${offset}px`,
                    transitionProperty: 'transform', // prevents offset bleed
                }}
                className={classNames(
                    'transform duration-300 ease-in-out bg-wp-theme-500 fixed h-32 left-0 p-8 w-full z-high',
                    { '-translate-y-full': !open },
                )}>
                <div className="h-full flex items-center justify-center text-3xl font-bold text-white">
                    Coming soon
                </div>
            </div>
        </>
    )
}
