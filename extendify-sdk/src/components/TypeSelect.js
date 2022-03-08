import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { useGlobalStore } from '@extendify/state/GlobalState'
import { useTemplatesStore } from '@extendify/state/Templates'

export const TypeSelect = ({ className }) => {
    const updateType = useTemplatesStore((state) => state.updateType)
    const currentType = useGlobalStore(
        (state) => state?.currentType ?? 'pattern',
    )

    return (
        <div className={className}>
            <h4 className="sr-only">{__('Type select', 'extendify')}</h4>
            <button
                type="button"
                className={classNames({
                    'button-focus m-0 min-w-sm cursor-pointer rounded-tl-sm rounded-bl-sm border border-black py-2.5 px-4 text-xs leading-none': true,
                    'bg-gray-900 text-white': currentType === 'pattern',
                    'bg-transparent text-black': currentType !== 'pattern',
                })}
                onClick={() => updateType('pattern')}>
                <span className="">{__('Patterns', 'extendify')}</span>
            </button>
            <button
                type="button"
                className={classNames({
                    'outline-none button-focus m-0 -ml-px min-w-sm cursor-pointer items-center rounded-tr-sm rounded-br-sm border border-black py-2.5 px-4 text-xs leading-none': true,
                    'bg-gray-900 text-white': currentType === 'template',
                    'bg-transparent text-black': currentType !== 'template',
                })}
                onClick={() => updateType('template')}>
                <span className="">{__('Page Layouts', 'extendify')}</span>
            </button>
        </div>
    )
}
