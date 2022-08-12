import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { useGlobalStore } from '@library/state/GlobalState'
import { useTemplatesStore } from '@library/state/Templates'

export const TypeSelect = ({ className }) => {
    const updateType = useTemplatesStore((state) => state.updateType)
    const currentType = useGlobalStore(
        (state) => state?.currentType ?? 'pattern',
    )

    return (
        <div className={className}>
            <h4 className="sr-only">{__('Type select', 'extendify')}</h4>
            <div className="flex justify-evenly border border-gray-900 p-0.5 rounded">
                <button
                    type="button"
                    className={classNames({
                        'w-full m-0 min-w-sm cursor-pointer rounded py-2.5 px-4 text-xs leading-none': true,
                        'bg-gray-900 text-white': currentType === 'pattern',
                        'bg-transparent text-black': currentType !== 'pattern',
                    })}
                    onClick={() => updateType('pattern')}>
                    <span className="">{__('Patterns', 'extendify')}</span>
                </button>
                <button
                    type="button"
                    className={classNames({
                        'outline-none w-full m-0 -ml-px min-w-sm cursor-pointer items-center rounded py-2.5 px-4 text-xs leading-none': true,
                        'bg-gray-900 text-white': currentType === 'template',
                        'bg-transparent text-black': currentType !== 'template',
                    })}
                    onClick={() => updateType('template')}>
                    <span className="">{__('Templates', 'extendify')}</span>
                </button>
            </div>
        </div>
    )
}
