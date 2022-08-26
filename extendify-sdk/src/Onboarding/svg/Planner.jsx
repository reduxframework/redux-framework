import { memo } from '@wordpress/element'

const Planner = (props) => {
    const { className, ...otherProps } = props

    return (
        <svg
            className={className}
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            {...otherProps}>
            <path opacity="0.3" d="M4 5H20V9H4V5Z" fill="currentColor" />
            <path
                d="M12 13H17V18H12V13ZM6 2H8V5H6V2ZM16 2H18V5H16V2Z"
                fill="currentColor"
            />
            <path
                d="M19 22H5C3.9 22 3 21.1 3 20V6C3 4.9 3.9 4 5 4H19C20.1 4 21 4.9 21 6V20C21 21.1 20.1 22 19 22ZM5 6V20H19V6H5Z"
                fill="currentColor"
            />
            <path d="M4 8H20V10H4V8Z" fill="currentColor" />
        </svg>
    )
}

export default memo(Planner)
