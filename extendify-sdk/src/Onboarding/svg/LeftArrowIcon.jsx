import { memo } from '@wordpress/element'

const LeftArrowIcon = (props) => {
    const { className, ...otherProps } = props

    return (
        <svg
            className={`icon ${className}`}
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            {...otherProps}>
            <path
                d="M10 17.5L15 12L10 6.5"
                stroke="#1f1f1f"
                strokeWidth="1.75"
            />
        </svg>
    )
}

export default memo(LeftArrowIcon)
