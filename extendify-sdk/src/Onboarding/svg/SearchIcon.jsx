import { memo } from '@wordpress/element'

const SearchIcon = (props) => {
    const { className, ...otherProps } = props

    return (
        <svg
            className={className}
            width="24"
            height="24"
            viewBox="0 0 24 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            {...otherProps}>
            <path
                d="M8 18.5504L12 14.8899"
                stroke="#1E1E1E"
                strokeWidth="1.5"
            />
            <path
                d="M20.25 11.7523C20.25 14.547 18.092 16.7546 15.5 16.7546C12.908 16.7546 10.75 14.547 10.75 11.7523C10.75 8.95754 12.908 6.75 15.5 6.75C18.092 6.75 20.25 8.95754 20.25 11.7523Z"
                stroke="#1E1E1E"
                strokeWidth="1.5"
            />
        </svg>
    )
}

export default memo(SearchIcon)
