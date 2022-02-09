import { Button } from '@wordpress/components'
import { useState, useEffect, useRef } from '@wordpress/element'
import { Icon } from '@wordpress/icons'
import { brandMark } from './icons'
import { __ } from '@wordpress/i18n'
import { openModal } from '../util/general'
import { useUserStore } from '../state/User'
import { useGlobalStore } from '../state/GlobalState'
import { General } from '../api/General'
import { NewImportsPopover } from './popovers/NewImportsPopover'

export const MainButton = () => {
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
            <Button
                isPrimary
                ref={buttonRef}
                style={{ padding: '12px' }}
                onClick={() => openModal('main-button')}
                id="extendify-templates-inserter-btn"
                icon={
                    <Icon
                        style={{ marginRight: '4px' }}
                        icon={brandMark}
                        size={24}
                    />
                }>
                {__('Library', 'extendify')}
            </Button>

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
            {__('Discover patterns in Extendify Library', 'extendify')}
        </Button>
    )
}
