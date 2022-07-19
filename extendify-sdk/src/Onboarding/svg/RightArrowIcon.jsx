import { memo } from '@wordpress/element'

const RightArrowIcon = (props) => {
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
                d="M15 17.5L10 12L15 6.5"
                stroke="currentColor"
                strokeWidth="1.75"
            />
        </svg>
    )
}

export default memo(RightArrowIcon)
