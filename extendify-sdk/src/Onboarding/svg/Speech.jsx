import { memo } from '@wordpress/element'

const Speech = (props) => {
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
                d="M21 11C21 6.6 17 3 12 3C7 3 3 6.6 3 11C3 15.4 7 19 12 19C12.7 19 13.4 18.9 14 18.8V21.3C16 20 20.5 16.5 21 11.9C21 11.6 21 11.3 21 11Z"
                fill="currentColor"
            />
            <path
                d="M13 23.1V20C7 20.6 2 16.3 2 11C2 6 6.5 2 12 2C17.5 2 22 6 22 11C22 11.3 22 11.6 21.9 12C21.3 17.5 15.6 21.4 14.5 22.2L13 23.1ZM15 17.6V19.3C16.9 17.8 19.6 15.1 20 11.7C20 11.5 20 11.2 20 11C20 7.1 16.4 4 12 4C7.6 4 4 7.1 4 11C4 15.4 8.6 18.9 13.8 17.8L15 17.6Z"
                fill="currentColor"
            />
        </svg>
    )
}

export default memo(Speech)
