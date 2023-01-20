import { useEffect, useState, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { getOption, updateOption } from '@assist/api/WPApi'
import { useDesignColors } from '@assist/hooks/useDesignColors'
import { useTasksStore } from '@assist/state/Tasks'

export const UpdateSiteDescription = ({ popModal, setModalTitle }) => {
    const [siteDescription, setSiteDescription] = useState(undefined)
    const [initialValue, setInitialValue] = useState(undefined)
    const inputRef = useRef()
    const { completeTask } = useTasksStore()
    const { mainColor } = useDesignColors()

    useEffect(() => {
        setModalTitle(__('Add site description', 'extendify'))
    }, [setModalTitle])

    useEffect(() => {
        getOption('blogdescription').then((text) => {
            setSiteDescription(text)
            setInitialValue(text)
        })
    }, [setSiteDescription])

    useEffect(() => {
        inputRef?.current?.focus()
    }, [initialValue])

    if (typeof siteDescription === 'undefined') {
        return <div className="h-32">{__('Loading...', 'extendify')}</div>
    }

    return (
        <form
            className="gap-6 flex flex-col"
            onSubmit={(e) => e.preventDefault()}>
            <div>
                <label
                    className="block mb-1 text-gray-900 text-sm"
                    htmlFor="extendify-site-description-input">
                    {__('Site description', 'extendify')}
                </label>
                <input
                    ref={inputRef}
                    type="text"
                    name="extendify-site-description-input"
                    id="extendify-site-description-input"
                    className="w-96 max-w-full border border-gray-900 px-2 h-12 input-focus"
                    onChange={(e) => {
                        setSiteDescription(e.target.value)
                    }}
                    value={siteDescription}
                    placeholder={__('Enter a site description...', 'extendify')}
                />
            </div>
            <div>
                <button
                    disabled={siteDescription === initialValue}
                    className={classNames(
                        'px-4 py-3 text-white button-focus border-0 rounded relative z-10 cursor-pointer w-1/5',
                        {
                            'opacity-50 cursor-default':
                                siteDescription === initialValue,
                        },
                    )}
                    style={{ backgroundColor: mainColor }}
                    onClick={async () => {
                        await updateOption('blogdescription', siteDescription)
                        completeTask('site-description')
                        popModal()
                    }}>
                    {__('Save', 'extendify')}
                </button>
            </div>
        </form>
    )
}
