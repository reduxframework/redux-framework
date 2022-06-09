import classNames from 'classnames'
import { Checkmark } from '@onboarding/svg'

export const Card = ({
    image,
    heading,
    name,
    description,
    selected,
    onClick,
    lock,
}) => {
    return (
        <div
            role={lock ? undefined : 'button'}
            tabIndex={lock ? undefined : 0}
            aria-label={lock ? undefined : name}
            className={classNames(
                'text-base p-0 bg-transparent overflow-hidden rounded-lg border border-gray-100',
                {
                    'button-focus': !lock,
                },
            )}
            onKeyDown={(e) => {
                if (['Enter', 'Space', ' '].includes(e.key)) {
                    if (!lock) onClick()
                }
            }}
            onClick={() => {
                if (!lock) onClick()
            }}>
            <div className="border-gray-100 border-b p-2 flex justify-between min-w-sm">
                <div
                    className={classNames('flex items-center', {
                        'text-gray-700': !selected,
                    })}>
                    <span>{name}</span>
                    {lock && (
                        <span className="w-4 h-4 text-base leading-none pl-2 mr-6 dashicons dashicons-lock"></span>
                    )}
                </div>
                {(lock || selected) && (
                    <Checkmark className="text-partner-primary-bg w-6" />
                )}
            </div>
            <div className="flex flex-col">
                <div
                    style={{ backgroundImage: `url(${image})` }}
                    className="h-32 bg-cover"
                />
                <div className="p-6 text-left">
                    <div className="text-base font-bold mb-2">{heading}</div>
                    <div className="text-sm">{description}</div>
                </div>
            </div>
        </div>
    )
}
