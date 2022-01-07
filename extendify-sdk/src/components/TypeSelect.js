import { __ } from '@wordpress/i18n'
import { useTemplatesStore } from '../state/Templates'
import classNames from 'classnames'
import { useGlobalStore } from '../state/GlobalState'

export const TypeSelect = ({ className }) => {
    const updateType = useTemplatesStore((state) => state.updateType)
    const currentType = useGlobalStore((state) =>
        state.currentType ? state.currentType : 'pattern',
    )

    return (
        <div className={className}>
            <h4 className="sr-only">{__('Type select', 'extendify')}</h4>
            <button
                type="button"
                className={classNames({
                    'cursor-pointer text-xs leading-none m-0 py-2.5 px-4 min-w-sm border rounded-tl-sm rounded-bl-sm border-black button-focus': true,
                    'bg-gray-900 text-white': currentType === 'pattern',
                    'bg-transparent text-black': currentType !== 'pattern',
                })}
                onClick={() => updateType('pattern')}>
                <span className="">{__('Patterns', 'extendify')}</span>
            </button>
            <button
                type="button"
                className={classNames({
                    'cursor-pointer text-xs leading-none m-0 py-2.5 px-4 min-w-sm items-center border rounded-tr-sm rounded-br-sm border-black outline-none -ml-px button-focus': true,
                    'bg-gray-900 text-white': currentType === 'template',
                    'bg-transparent text-black': currentType !== 'template',
                })}
                onClick={() => updateType('template')}>
                <span className="">{__('Page Layouts', 'extendify')}</span>
            </button>
        </div>
    )
}
