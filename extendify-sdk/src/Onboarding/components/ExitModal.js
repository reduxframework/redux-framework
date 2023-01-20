import { Button } from '@wordpress/components'
import { useRef, useState, useEffect } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'
import { Dialog } from '@headlessui/react'
import classNames from 'classnames'
import { shuffle } from 'lodash'
import { getExitQuestions } from '@onboarding/api/DataApi'
import { updateOption } from '@onboarding/api/WPApi'
import { useGlobalStore } from '@onboarding/state/Global'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { Checkmark } from '@onboarding/svg'

export const ExitModal = () => {
    const { exitModalOpen, closeExitModal, hoveredOverExitButton } =
        useGlobalStore()
    const { setExitFeedback } = useUserSelectionStore()
    const [value, setValue] = useState(null)
    const [customOther, setCustomOther] = useState('')
    const [options, setOptions] = useState([])
    const initialFocus = useRef()
    const submitExitSurvey = () => {
        setExitFeedback(
            value === __('Other', 'extendify')
                ? `Other: ${customOther}`
                : value,
        )
        skipLaunch()
    }

    const skipLaunch = async () => {
        // Store when Launch is skipped.
        await updateOption(
            'extendify_onboarding_skipped',
            new Date().toISOString(),
        )
        location.href = window.extOnbData.adminUrl
    }

    useEffect(() => {
        if (!hoveredOverExitButton) return
        // Intentionally not using SWR so we only try once.
        getExitQuestions()
            .then(({ data }) => {
                if (!Array.isArray(data) || !data[0]?.key) {
                    throw new Error('Invalid data')
                }
                setOptions([
                    ...shuffle(data.filter((d) => d.key !== 'Other')),
                    { key: 'Other', label: __('Other', 'extendify') },
                ])
            })
            .catch(() => {
                const backupQuestions = [
                    {
                        key: 'I still want it, just disabling temporary',
                        label: __(
                            'I still want it, just disabling temporary',
                            'extendify',
                        ),
                    },
                    {
                        key: 'I plan on using my own theme or builder',
                        label: __(
                            'I plan on using my own theme or builder',
                            'extendify',
                        ),
                    },
                    {
                        key: "The theme designs don't look great",
                        label: __(
                            "The theme designs don't look great",
                            'extendify',
                        ),
                    },
                ]
                setOptions([
                    ...shuffle(backupQuestions),
                    { key: 'Other', label: __('Other', 'extendify') },
                ])
            })
            .finally(() => {
                initialFocus.current?.focus()
            })
    }, [hoveredOverExitButton])

    return (
        <Dialog
            as="div"
            className="extendify-onboarding"
            open={exitModalOpen}
            initialFocus={initialFocus}
            onClose={closeExitModal}>
            <div className="absolute top-0 mx-auto w-full h-full overflow-hidden p-2 md:p-6 md:flex justify-center items-center z-max">
                <div
                    className="fixed inset-0 bg-black bg-opacity-40 transition-opacity"
                    aria-hidden="true"
                />
                <Dialog.Title className="sr-only">
                    {__('Exit Launch', 'extendify')}
                </Dialog.Title>
                <form
                    onSubmit={submitExitSurvey}
                    style={{ maxWidth: '400px' }}
                    className="sm:flex relative shadow-2xl sm:overflow-hidden mx-auto bg-white flex flex-col p-8"
                    data-test="exit-survey-form">
                    <Button
                        className="absolute top-0 right-0"
                        onClick={closeExitModal}
                        icon={<Icon icon={close} size={24} />}
                        label={__('Exit Launch', 'extendify')}
                        data-test="close-exit-survey"
                    />
                    <p className="m-0 text-lg font-bold text-left">
                        {sprintf(
                            // translators: %s: The name of the plugin, Extendify.
                            __(
                                'Thanks for trying %s Launch. How can we make this better?',
                                'extendify',
                            ),
                            'Extendify',
                        )}
                    </p>
                    <div
                        role="radiogroup"
                        className="flex flex-col text-base mt-4">
                        {options.map(({ key, label }, i) => (
                            <LabeledCheckbox
                                initialFocus={i ? undefined : initialFocus}
                                key={key}
                                slug={key}
                                label={label}
                                value={value}
                                customOther={customOther}
                                setCustomOther={setCustomOther}
                                setValue={(v) =>
                                    setValue((previousValue) =>
                                        previousValue === v ? null : v,
                                    )
                                }
                            />
                        ))}
                    </div>
                    <div className="flex justify-end mt-8">
                        {/* If they've made a choice, we are sending it in real time */}
                        {value ? null : (
                            <button
                                className="px-4 py-3 mr-4 button-focus"
                                type="button"
                                onClick={skipLaunch}
                                data-test="skip-exit-survey">
                                {__('Skip', 'extendify')}
                            </button>
                        )}
                        <button
                            className={classNames(
                                'px-4 py-3 font-bold bg-partner-primary-bg text-partner-primary-text button-focus',
                                {
                                    'opacity-50 cursor-default': !value,
                                },
                            )}
                            type="button"
                            onClick={submitExitSurvey}
                            disabled={!value}
                            data-test="submit-exit-survey">
                            {__('Submit', 'extendify')}
                        </button>
                    </div>
                </form>
            </div>
        </Dialog>
    )
}

const LabeledCheckbox = ({
    label,
    slug,
    setValue,
    value,
    customOther,
    setCustomOther,
    initialFocus,
}) => {
    const needsInput = slug === 'Other'
    const checked = value === slug
    const id = slug
        .toLowerCase()
        .replace(/ /g, '-')
        .replace(/[^\w-]+/g, '')

    return (
        <>
            <span
                className="flex items-center leading-loose"
                data-test="exit-survey-item">
                <span
                    onClick={() => setValue(slug)}
                    onKeyDown={(e) => {
                        if (e.key === 'Enter' || e.key === ' ') {
                            setValue(slug)
                        }
                    }}
                    role="radio"
                    aria-labelledby={id}
                    aria-checked={checked}
                    data-value={slug}
                    // Will focus on the first element in the list
                    ref={initialFocus}
                    tabIndex={0}
                    className="w-5 h-5 relative mr-2">
                    <span className="h-5 w-5 rounded-sm m-0 block border border-gray-900 button-focus" />
                    <Checkmark
                        className={classNames('absolute -top-0.5 -left-0.5', {
                            'text-partner-primary-bg': checked,
                        })}
                        style={{ width: 24, color: '#fff' }}
                        role="presentation"
                    />
                </span>
                <span onClick={() => setValue(slug)} id={id}>
                    {label}
                </span>
            </span>
            {needsInput && checked ? (
                <textarea
                    value={customOther}
                    onChange={(e) => setCustomOther(e.target.value)}
                    className="border border-gray-400 mt-2 text-base p-2"
                    rows="4"
                />
            ) : null}
        </>
    )
}
