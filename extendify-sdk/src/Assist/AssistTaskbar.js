import { createPortal } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { TaskbarBody } from '@assist/components/TaskbarBody'
import { useAdminColors } from '@assist/hooks/useAdminColors'
import { useTaskbarStore } from '@assist/state/Taskbar'

export const AssistTaskbar = () => {
    const { open, toggleOpen } = useTaskbarStore()
    useAdminColors()
    const handleClick = () => {
        window.extAssistData.devbuild
            ? toggleOpen()
            : window.location.replace(
                  window.extAssistData.adminUrl +
                      'admin.php?page=extendify-assist',
              )
    }

    return (
        <>
            <div className="extendify-assist">
                <button
                    type="button"
                    onClick={handleClick}
                    className={classNames(
                        'px-4 border-0 text-white cursor-pointer hover:bg-wp-theme-500 focus:bg-wp-theme-500 focus:outline-none transition duration-200 ease-in-out inline-flex justify-center items-center gap-2 overflow-hidden',
                        {
                            'bg-wp-theme-500': open,
                            'bg-transparent': !open,
                        },
                    )}>
                    <span
                        className="w-4 h-4 bg-no-repeat bg-center inline-block"
                        style={{
                            backgroundImage:
                                'url("data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMCAyMCIgZmlsbD0iI2ZmZiIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4gPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0xMS41MDA5IDJIMTQuOTg3M0MxNS45NzQ3IDIgMTYuMzMyMiAyLjEwMTYxIDE2LjY5MzQgMi4yOTEyN0MxNy4wNTQ2IDIuNDgxNjkgMTcuMzM3MiAyLjc2MDkyIDE3LjUzMDQgMy4xMTYxNkMxNy43MjM3IDMuNDcyMTYgMTcuODI2IDMuODI0NCAxNy44MjYgNC43OTc1NlY4LjIzMzM2QzE3LjgyNiA5LjIwNjUyIDE3LjcyMjkgOS41NTg3NiAxNy41MzA0IDkuOTE0NzVDMTcuMzM3MiAxMC4yNzA4IDE3LjA1MzkgMTAuNTQ5MiAxNi42OTM0IDEwLjczOTZDMTYuNTMxOSAxMC44MjQ4IDE2LjM3MDggMTAuODk0OCAxNi4xNTQgMTAuOTQ1VjEzLjY3OTRDMTYuMTU0IDE1LjE4MjQgMTUuOTk0NyAxNS43MjY0IDE1LjY5NzUgMTYuMjc2MkMxNS4zOTkxIDE2LjgyNiAxNC45NjE1IDE3LjI1NjEgMTQuNDA0OCAxNy41NTAyQzEzLjg0NjkgMTcuODQ0MiAxMy4yOTQ5IDE4IDExLjc2OTggMThINi4zODUzOEM0Ljg2MDI4IDE4IDQuMzA4MjggMTcuODQzMSAzLjc1MDM4IDE3LjU1MDJDMy4xOTI0NyAxNy4yNTYxIDIuNzU2MDYgMTYuODI0OCAyLjQ1NzY1IDE2LjI3NjJDMi4xNTkyMyAxNS43Mjc1IDIgMTUuMTgyNCAyIDEzLjY3OTRWOC4zNzQyNkMyIDYuODcxMjkgMi4xNTkyMyA2LjMyNzI5IDIuNDU2NDcgNS43Nzc0OEMyLjc1NDg4IDUuMjI3NjcgMy4xOTI0NyA0Ljc5NjQyIDMuNzUwMzggNC41MDIzNEM0LjMwNzEgNC4yMDk0MSA0Ljg2MDI4IDQuMDUyNDkgNi4zODUzOCA0LjA1MjQ5SDguNjkwODFDOC43MzQwNSAzLjYwNTk3IDguODI0MjYgMy4zNjIzNCA4Ljk1Njk0IDMuMTE2OTJDOS4xNTAxNiAyLjc2MDkyIDkuNDMzNSAyLjQ4MTY5IDkuNzk0NzQgMi4yOTEyN0MxMC4xNTUyIDIuMTAxNjEgMTAuNTEzNCAyIDExLjUwMDkgMlpNOS43MDkgNC4xODY5OEM5LjcwOSAzLjU0OTI5IDEwLjIzMzYgMy4wMzIzNCAxMC44ODA3IDMuMDMyMzRIMTUuNjA2NkMxNi4yNTM4IDMuMDMyMzQgMTYuNzc4NCAzLjU0OTI5IDE2Ljc3ODQgNC4xODY5OFY4Ljg0Mzk1QzE2Ljc3ODQgOS40ODE2NCAxNi4yNTM4IDkuOTk4NTkgMTUuNjA2NiA5Ljk5ODU5SDEwLjg4MDdDMTAuMjMzNiA5Ljk5ODU5IDkuNzA5IDkuNDgxNjQgOS43MDkgOC44NDM5NVY0LjE4Njk4WiIgZmlsbD0iI2ZmZiIgLz4gPC9zdmc+")',
                        }}
                    />
                    <span>{__('Site Assistant', 'extendify')}</span>
                </button>
            </div>
            {window.extAssistData?.devbuild && <TaskbarPortal />}
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
