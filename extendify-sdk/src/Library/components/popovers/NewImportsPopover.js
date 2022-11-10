import { Button, Popover } from '@wordpress/components'
import { safeHTML } from '@wordpress/dom'
import { __, sprintf } from '@wordpress/i18n'
import { Icon, close } from '@wordpress/icons'

export const NewImportsPopover = ({
    anchorRef,
    onPressX,
    onClick,
    onClickOutside,
}) => {
    if (!anchorRef.current) return null
    return (
        <Popover
            anchorRef={anchorRef.current}
            shouldAnchorIncludePadding={true}
            className="extendify-tooltip-default"
            focusOnMount={false}
            onFocusOutside={onClickOutside}
            onClick={onClick}
            position="bottom center"
            noArrow={false}>
            <>
                <div
                    style={{
                        display: 'flex',
                        justifyContent: 'space-between',
                        alignItems: 'center',
                        marginBottom: '0.5rem',
                    }}>
                    <span
                        style={{
                            textTransform: 'uppercase',
                            color: '#8b8b8b',
                        }}>
                        {__('Monthly Imports', 'extendify')}
                    </span>
                    <Button
                        style={{
                            color: 'white',
                            position: 'relative',
                            right: '-5px',
                            padding: '0',
                            minWidth: '0',
                            height: '20px',
                            width: '20px',
                        }}
                        onClick={(event) => {
                            event.stopPropagation()
                            onPressX()
                        }}
                        icon={<Icon icon={close} size={12} />}
                        showTooltip={false}
                        label={__('Close callout', 'extendify')}
                    />
                </div>
                <div
                    dangerouslySetInnerHTML={{
                        __html: safeHTML(
                            sprintf(
                                // translators: %s: <strong> tags
                                __(
                                    "%1$sGood news!%2$s We've added more imports to your library. Enjoy!",
                                    'extendify',
                                ),
                                '<strong>',
                                '</strong>',
                            ),
                        ),
                    }}
                />
            </>
        </Popover>
    )
}
