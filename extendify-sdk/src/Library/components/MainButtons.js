import { Button } from '@wordpress/components'
import { useState, useEffect, useRef } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { Icon } from '@wordpress/icons'
import { General } from '@library/api/General'
import { useGlobalStore } from '@library/state/GlobalState'
import { useUserStore } from '@library/state/User'
import { openModal } from '@library/util/general'
import { brandMark } from './icons'
import { NewImportsPopover } from './popovers/NewImportsPopover'

export const MainButtonWrapper = () => {
    const [showTooltip, setShowTooltip] = useState(false)
    const once = useRef(false)
    const buttonRef = useRef()
    const loggedIn = useUserStore((state) => state.apiKey.length)
    const hasImported = useUserStore((state) => state.imports > 0)
    const open = useGlobalStore((state) => state.open)
    const hasPendingNewImports = useUserStore(
        (state) => state.allowedImports === 0,
    )

    const handleTooltipClose = async () => {
        await General.ping('mb-tooltip-closed')
        setShowTooltip(false)
        // If they close the tooltip, we can set the allowed imports
        // to -1 and when it opens it will fetch and update. Meanwhile,
        // -1 will be ignored by the this component.
        useUserStore.setState({
            allowedImports: -1,
        })
    }

    useEffect(() => {
        if (open) {
            setShowTooltip(false)
            once.current = true
        }
        if (!loggedIn && hasPendingNewImports && hasImported) {
            once.current || setShowTooltip(true)
            once.current = true
        }
    }, [loggedIn, hasPendingNewImports, hasImported, open])

    return (
        <>
            <MainButton
                buttonRef={buttonRef}
                text={__('Design Library', 'extendify')}
            />
            {showTooltip && (
                <NewImportsPopover
                    anchorRef={buttonRef}
                    onClick={async () => {
                        await General.ping('mb-tooltip-pressed')
                        openModal('main-button-tooltip')
                    }}
                    onPressX={handleTooltipClose}
                />
            )}
        </>
    )
}
const MainButton = ({ buttonRef, text }) => {
    return (
        <div className="extendify">
            <Button
                isPrimary
                ref={buttonRef}
                className="h-8 xs:h-9 px-1 min-w-0 xs:pl-2 xs:pr-3 sm:ml-2"
                onClick={() => openModal('main-button')}
                id="extendify-templates-inserter-btn"
                icon={
                    <Icon
                        icon={brandMark}
                        size={24}
                        style={{ marginRight: 0 }}
                    />
                }>
                <span className="hidden xs:inline ml-1">{text}</span>
            </Button>
        </div>
    )
}
export const CtaButton = () => {
    return (
        <Button
            id="extendify-cta-button"
            style={{
                margin: '1rem 1rem 0',
                width: 'calc(100% - 2rem)',
                justifyContent: ' center',
            }}
            onClick={() => openModal('patterns-cta')}
            isSecondary>
            {sprintf(
                // translators: %s: Extendify Library term.
                __('Discover patterns in the %s', 'extendify'),
                'Extendify Library',
            )}
        </Button>
    )
}
