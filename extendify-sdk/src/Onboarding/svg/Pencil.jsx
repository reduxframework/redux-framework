import { memo } from '@wordpress/element'

const Pencil = (props) => {
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
                d="M7.03432 14.8828L16.2343 5.68249L18.2298 7.67791L9.02981 16.8782L7.03432 14.8828Z"
                fill="currentColor"
            />
            <path
                d="M3.669 17L3 21L7 20.331L3.669 17ZM21.707 4.879L19.121 2.293C18.926 2.098 18.67 2 18.414 2C18.158 2 17.902 2.098 17.707 2.293L5 15C5 15 6.005 15.005 6.5 15.5C6.995 15.995 6.984 16.984 6.984 16.984C6.984 16.984 8.003 17.003 8.5 17.5C8.997 17.997 9 19 9 19L21.707 6.293C22.098 5.902 22.098 5.269 21.707 4.879ZM8.686 15.308C8.588 15.05 8.459 14.789 8.289 14.539L15.951 6.877L17.123 8.049L9.461 15.711C9.21 15.539 8.946 15.408 8.686 15.308ZM18.537 6.635L17.365 5.463L18.414 4.414L19.586 5.586L18.537 6.635Z"
                fill="currentColor"
            />
        </svg>
    )
}

export default memo(Pencil)
