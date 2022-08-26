import { memo } from '@wordpress/element'

const Design = (props) => {
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
                d="M11.5003 15.5L15.5003 11.4998L20.0004 15.9998L16.0004 19.9999L11.5003 15.5Z"
                fill="currentColor"
            />
            <path
                opacity="0.3"
                d="M3.93958 7.94043L7.93961 3.94026L12.4397 8.44021L8.43968 12.4404L3.93958 7.94043Z"
                fill="currentColor"
            />
            <path
                d="M8.575 11.747L4.828 8L8 4.828L11.747 8.575L13.161 7.161L8 2L2 8L7.161 13.161L8.575 11.747ZM16.769 10.769L15.355 12.183L19.172 16L16 19.172L12.183 15.355L10.769 16.769L16 22L22 16L16.769 10.769Z"
                fill="currentColor"
            />
            <path
                d="M21.707 4.879L19.121 2.293C18.926 2.098 18.67 2 18.414 2C18.158 2 17.902 2.098 17.707 2.293L3 17V21H7L21.707 6.293C22.098 5.902 22.098 5.269 21.707 4.879ZM6.172 19H5V17.828L15.707 7.121L16.879 8.293L6.172 19ZM18.293 6.879L17.121 5.707L18.414 4.414L19.586 5.586L18.293 6.879Z"
                fill="currentColor"
            />
        </svg>
    )
}

export default memo(Design)
