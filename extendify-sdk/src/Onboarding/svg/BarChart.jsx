import { memo } from '@wordpress/element'

const BarChart = (props) => {
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
                d="M3 13H7V19H3V13ZM10 9H14V19H10V9ZM17 5H21V19H17V5Z"
                fill="currentColor"
            />
            <path
                d="M14 8H10C9.448 8 9 8.448 9 9V19C9 19.552 9.448 20 10 20H14C14.552 20 15 19.552 15 19V9C15 8.448 14.552 8 14 8ZM13 18H11V10H13V18ZM21 4H17C16.448 4 16 4.448 16 5V19C16 19.552 16.448 20 17 20H21C21.552 20 22 19.552 22 19V5C22 4.448 21.552 4 21 4ZM20 18H18V6H20V18ZM7 12H3C2.448 12 2 12.448 2 13V19C2 19.552 2.448 20 3 20H7C7.552 20 8 19.552 8 19V13C8 12.448 7.552 12 7 12ZM6 18H4V14H6V18Z"
                fill="currentColor"
            />
        </svg>
    )
}

export default memo(BarChart)
