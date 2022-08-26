import { memo } from '@wordpress/element'

const PriceTag = (props) => {
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
                d="M20 11.414L10.707 20.707C10.518 20.896 10.267 21 10 21C9.733 21 9.482 20.896 9.293 20.707L3.293 14.707C3.104 14.518 3 14.267 3 14C3 13.733 3.104 13.482 3.293 13.293L12.586 4H20V11.414Z"
                fill="currentColor"
            />
            <path
                d="M10 22C9.466 22 8.964 21.792 8.586 21.414L2.586 15.414C2.208 15.036 2 14.534 2 14C2 13.466 2.208 12.964 2.586 12.586L12.172 3H21V11.828L11.414 21.414C11.036 21.792 10.534 22 10 22ZM13 5L4 14L10 20L19 11V5H13Z"
                fill="currentColor"
            />
            <path
                d="M16 7C15.7348 7 15.4804 7.10536 15.2929 7.29289C15.1054 7.48043 15 7.73478 15 8C15 8.26522 15.1054 8.51957 15.2929 8.70711C15.4804 8.89464 15.7348 9 16 9C16.2652 9 16.5196 8.89464 16.7071 8.70711C16.8946 8.51957 17 8.26522 17 8C17 7.73478 16.8946 7.48043 16.7071 7.29289C16.5196 7.10536 16.2652 7 16 7Z"
                fill="currentColor"
            />
        </svg>
    )
}

export default memo(PriceTag)
