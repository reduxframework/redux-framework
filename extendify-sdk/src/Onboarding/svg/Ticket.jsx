import { memo } from '@wordpress/element'

const Ticket = (props) => {
    const { className, ...otherProps } = props

    return (
        <svg
            className={className}
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            {...otherProps}>
            <path
                d="M22 10V6C22 4.9 21.11 4 20 4H4C2.9 4 2 4.9 2 6V10C3.1 10 4 10.9 4 12C4 13.1 3.1 14 2 14V18C2 19.1 2.9 20 4 20H20C21.11 20 22 19.1 22 18V14C20.89 14 20 13.1 20 12C20 10.9 20.89 10 22 10ZM20 8.54C18.81 9.23 18 10.52 18 12C18 13.48 18.81 14.77 20 15.46V18H4V15.46C5.19 14.77 6 13.48 6 12C6 10.52 5.19 9.23 4 8.54V6H20V8.54Z"
                fill="currentColor"
            />
            <path
                opacity="0.3"
                d="M18 12C18 13.48 18.81 14.77 20 15.46V18H4V15.46C5.19 14.77 6 13.48 6 12C6 10.52 5.19 9.23 4 8.54V6H20V8.54C18.81 9.23 18 10.52 18 12Z"
                fill="currentColor"
            />
        </svg>
    )
}

export default memo(Ticket)
