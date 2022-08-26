import { memo } from '@wordpress/element'

const Shop = (props) => {
    const { className, ...otherProps } = props

    return (
        <svg
            className={className}
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            {...otherProps}>
            <path
                opacity="0.3"
                d="M19.27 8H4.73L3 13.2V14H21V13.2L19.27 8ZM5 4H19V8H5V4Z"
                fill="currentColor"
            />
            <path d="M13 21H3V13H13V21ZM5 19H11V15H5V19Z" fill="currentColor" />
            <path
                d="M22 15H2V13.038L4.009 7H19.991L22 13.038V15ZM4.121 13H19.88L18.549 9H5.451L4.121 13Z"
                fill="currentColor"
            />
            <path
                d="M19 14H21V21H19V14ZM20 9H4V3H20V9ZM6 7H18V5H6V7Z"
                fill="currentColor"
            />
        </svg>
    )
}

export default memo(Shop)
