import { Fragment } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { useDesignColors } from '@assist/hooks/useDesignColors'
import { useGlobalStore } from '../state/Global'

const steps = {
    'site-type': {
        step: __('Site Industry', 'extendify'),
        title: __("Let's Start Building Your Website", 'extendify'),
        description: __(
            'Create a super-fast, beautiful, and fully customized site in minutes with the Launch in the core WordPress editor.',
            'extendify',
        ),
        buttonText: __('Select Site Industry', 'extendify'),
    },
    goals: {
        step: __('Goals', 'extendify'),
        title: __('Continue Building Your Website', 'extendify'),
        description: __(
            'Create a super-fast, beautiful, and fully customized site in minutes with the Launch in the core WordPress editor.',
            'extendify',
        ),
        buttonText: __('Select Site Goals', 'extendify'),
    },
    style: {
        step: __('Design', 'extendify'),
        title: __('Continue Building Your Website', 'extendify'),
        description: __(
            'Create a super-fast, beautiful, and fully customized site in minutes with the Launch in the core WordPress editor.',
            'extendify',
        ),
        buttonText: __('Select Site Design', 'extendify'),
    },
    pages: {
        step: __('Pages', 'extendify'),
        title: __('Continue Building Your Website', 'extendify'),
        description: __(
            'Create a super-fast, beautiful, and fully customized site in minutes with the Launch in the core WordPress editor.',
            'extendify',
        ),
        buttonText: __('Select Site Pages', 'extendify'),
    },
    confirmation: {
        step: __('Launch', 'extendify'),
        title: __("Let's Launch Your Site", 'extendify'),
        description: __(
            "You're one step away from creating a super-fast, beautiful, and fully customizable site with the Launch in the core WordPress editor.",
            'extendify',
        ),
        buttonText: __('Launch The Site', 'extendify'),
    },
}

export const AdminNotice = () => {
    const noticeKey = 'extendify-launch'
    const { isDismissed, dismissNotice } = useGlobalStore()
    const { mainColor: bgColor, darkColor: bgDarker } = useDesignColors()

    // To avoid content flash, we load in this partial piece of state early via php
    const dismissed = window.extAssistData.dismissedNotices.find(
        (notice) => notice.id === noticeKey,
    )
    if (dismissed || isDismissed(noticeKey)) return null

    const pageData = JSON.parse(localStorage.getItem('extendify-pages') ?? null)
    if (!pageData) return null

    // Filter out pages that don't match step keys
    const pages = pageData?.state?.availablePages.filter((p) =>
        Object.keys(steps).includes(p),
    )
    // If their last step doesn't exist in our options, just use step 1
    const lastStep = pageData?.state?.currentPageSlug
    const currentStep = Object.keys(steps).includes(lastStep)
        ? lastStep
        : 'site-type'

    let reached = false

    return (
        <div
            className="mt-6 mb-8 max-w-screen-3xl"
            data-test="assist-admin-notice">
            <div
                style={{ background: bgColor, minHeight: '420px' }}
                className="relative flex flex-col">
                <div
                    style={{ background: bgDarker }}
                    className="justify-between items-center py-6 px-2 lg:px-12 gap-x-3 hidden md:flex">
                    {pages.map((item, index) => {
                        if (item === currentStep) reached = true
                        return (
                            <Fragment key={item}>
                                <StepCircle
                                    reached={reached}
                                    bgColor={bgColor}
                                    step={index + 1}
                                    stepName={steps[item]?.step}
                                    current={item === currentStep}
                                />
                                {index !== pages.length - 1 && (
                                    <div className="hidden lg:block border-0 border-b-2 border-white border-solid border-opacity-10 h-0 grow w-full text-white" />
                                )}
                            </Fragment>
                        )
                    })}
                </div>
                <div
                    className="h-10 md:hidden"
                    aria-hidden={true}
                    style={{ backgroundColor: bgDarker }}
                />
                <div className="max-w-screen-md flex-grow w-full flex flex-col justify-center lg:w-1/2 p-6 lg:p-12">
                    <h2 className="text-3xl m-0 mb-4 text-white">
                        {steps[currentStep]?.title}
                    </h2>
                    <p className="text-lg text-white">
                        {steps[currentStep]?.description}
                    </p>
                    <div className="flex items-center gap-x-4 mt-9">
                        <a
                            href={`${window.extAssistData.adminUrl}admin.php?page=extendify-launch`}
                            className="cursor-pointer rounded-sm px-6 py-4 bg-gray-900 text-lg text-white border-none no-underline">
                            {steps[currentStep]?.buttonText}
                        </a>
                        <div>
                            <button
                                className="text-white p-1 text-sm z-10 flex items-center uppercase opacity-70 hover:opacity-100 bg-transparent border-none cursor-pointer"
                                onClick={() => dismissNotice(noticeKey)}
                                type="button">
                                <span className="dashicons dashicons-no-alt"></span>
                                <span className="tracking-wide">
                                    {__('Dismiss', 'extendify')}
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
                <img
                    className="lg:absolute bottom-0 right-0 max-w-lg w-full block mx-auto"
                    src={
                        window.extAssistData.asset_path +
                        '/extendify-preview.png'
                    }
                />
            </div>
        </div>
    )
}

const StepCircle = ({ reached, step, current, stepName, bgColor }) => (
    <div className="flex-none text-white text-sm flex items-center gap-x-2">
        <span
            style={{ background: reached ? undefined : bgColor }}
            className={classNames(
                'border-2 border-solid w-9 h-9 rounded-full flex items-center justify-center',
                {
                    'disc-checked border-white border-opacity-10': !reached,
                    'disc-number border-white': reached,
                    'border-dotted': current,
                },
            )}>
            {reached ? step : <span className="dashicons dashicons-saved" />}
        </span>
        <span>{stepName}</span>
    </div>
)
