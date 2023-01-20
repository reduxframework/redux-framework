import { createPortal } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { TaskbarBody } from '@assist/components/TaskbarBody'
import { useDesignColors } from '@assist/hooks/useDesignColors'
import { useTaskbarStore } from '@assist/state/Taskbar'
import { LogoIcon } from '@assist/svg'
import { TaskBadge } from './components/TaskBadge'

export const AssistTaskbar = () => {
    const { open, toggleOpen } = useTaskbarStore()
    const { darkColor } = useDesignColors()

    return (
        <>
            <div className="extendify-assist">
                <button
                    type="button"
                    onClick={toggleOpen}
                    style={{
                        backgroundColor: open ? darkColor : 'inherit',
                    }}
                    className={classNames(
                        'px-4 border-0 text-white cursor-pointer hover:bg-design-dark focus:bg-design-dark focus:outline-none transition duration-200 ease-in-out inline-flex justify-center items-center gap-2 overflow-hidden group',
                        {
                            'bg-design-dark': open,
                        },
                    )}>
                    <span className="w-4 h-4 flex items-center justify-center">
                        <LogoIcon />
                    </span>
                    <span>{__('Site Assistant', 'extendify')}</span>
                    <span className="flex items-center justify-center">
                        <TaskBadge
                            className={classNames(
                                'text-white p-0.5 h-5 w-5 rounded-full inline-flex items-center justify-center transition duration-200 ease-in-out text-xss group-focus:bg-gray-900 group-hover:bg-gray-900',
                                {
                                    'bg-design-dark': !open,
                                    'bg-gray-900': open,
                                },
                            )}
                        />
                    </span>
                </button>
            </div>
            <TaskbarPortal />
        </>
    )
}
document.body.prepend(
    Object.assign(document.createElement('div'), {
        id: 'extendify-assist-taskbar-portal',
        className: 'extendify-assist',
    }),
)
const TaskbarPortal = () => {
    return createPortal(
        <TaskbarBody />,
        document.getElementById('extendify-assist-taskbar-portal'),
    )
}
