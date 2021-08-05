import { __ } from '@wordpress/i18n'
import { useTemplatesStore } from '../state/Templates'
import classNames from 'classnames'

export default function TypeSelect() {
    const updateSearchParams = useTemplatesStore(state => state.updateSearchParams)
    const searchParams = useTemplatesStore(state => state.searchParams)
    const updateType = (type) => updateSearchParams({
        type: type,
    })
    return <div className="text-left w-full bg-white px-6 sm:px-0 pb-4 sm:pb-6 mt-px border-b sm:border-0">
        <h4 className="sr-only">{__('Type select', 'extendify-sdk')}</h4>
        <button type="button" className={classNames({
            'cursor-pointer p-3.5 space-x-2 inline-flex items-center border border-black button-focus': true,
            'bg-gray-900 text-white': searchParams.type === 'pattern',
            'bg-transparent text-black': searchParams.type !== 'pattern',
        })}
        onClick={() => updateType('pattern')}>
            <svg width="17" height="13" viewBox="0 0 17 13" className="fill-current" xmlns="http://www.w3.org/2000/svg">
                <path d="M1 13H16C16.55 13 17 12.55 17 12V8C17 7.45 16.55 7 16 7H1C0.45 7 0 7.45 0 8V12C0 12.55 0.45 13 1 13ZM0 1V5C0 5.55 0.45 6 1 6H16C16.55 6 17 5.55 17 5V1C17 0.45 16.55 0 16 0H1C0.45 0 0 0.45 0 1Z"/>
            </svg>
            <span className="">{__('Patterns', 'extendify-sdk')}</span>
        </button>
        <button type="button" className={classNames({
            'cursor-pointer p-3.5 px-4 space-x-2 inline-flex items-center border border-black focus:ring-2 focus:ring-wp-theme-500 ring-offset-1 outline-none -ml-px': true,
            'bg-gray-900 text-white': searchParams.type === 'template',
            'bg-transparent text-black': searchParams.type !== 'template',
        })}
        onClick={() => updateType('template')}>
            <svg width="17" height="13" viewBox="0 0 17 13" className="fill-current" xmlns="http://www.w3.org/2000/svg">
                <path d="M7 13H10C10.55 13 11 12.55 11 12V8C11 7.45 10.55 7 10 7H7C6.45 7 6 7.45 6 8V12C6 12.55 6.45 13 7 13ZM1 13H4C4.55 13 5 12.55 5 12V1C5 0.45 4.55 0 4 0H1C0.45 0 0 0.45 0 1V12C0 12.55 0.45 13 1 13ZM13 13H16C16.55 13 17 12.55 17 12V8C17 7.45 16.55 7 16 7H13C12.45 7 12 7.45 12 8V12C12 12.55 12.45 13 13 13ZM6 1V5C6 5.55 6.45 6 7 6H16C16.55 6 17 5.55 17 5V1C17 0.45 16.55 0 16 0H7C6.45 0 6 0.45 6 1Z"/>
            </svg>
            <span className="">{__('Page templates', 'extendify-sdk')}</span>
        </button>
        {/* <button type="button" className={classNames({
                'cursor-pointer p-3.5 px-4 space-x-2 inline-flex items-center border border-black focus:ring-2 focus:ring-wp-theme-500 ring-offset-1 outline-none -ml-px': true,
                'bg-gray-900 text-white': searchParams.type === 'sitekit',
                'bg-transparent text-black': searchParams.type !== 'sitekit',
            })}
            onClick={() => updateType('sitekit')}>
                <svg width="17" height="13" viewBox="0 0 17 13" className="fill-current" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 6H4C4.55 6 5 5.55 5 5V1C5 0.45 4.55 0 4 0H1C0.45 0 0 0.45 0 1V5C0 5.55 0.45 6 1 6ZM1 13H4C4.55 13 5 12.55 5 12V8C5 7.45 4.55 7 4 7H1C0.45 7 0 7.45 0 8V12C0 12.55 0.45 13 1 13ZM7 13H10C10.55 13 11 12.55 11 12V8C11 7.45 10.55 7 10 7H7C6.45 7 6 7.45 6 8V12C6 12.55 6.45 13 7 13ZM13 13H16C16.55 13 17 12.55 17 12V8C17 7.45 16.55 7 16 7H13C12.45 7 12 7.45 12 8V12C12 12.55 12.45 13 13 13ZM7 6H10C10.55 6 11 5.55 11 5V1C11 0.45 10.55 0 10 0H7C6.45 0 6 0.45 6 1V5C6 5.55 6.45 6 7 6ZM12 1V5C12 5.55 12.45 6 13 6H16C16.55 6 17 5.55 17 5V1C17 0.45 16.55 0 16 0H13C12.45 0 12 0.45 12 1Z"/>
                </svg>
                <span className="">{__('Site kits', 'extendify-sdk')}</span>
            </button> */}
    </div>
}
