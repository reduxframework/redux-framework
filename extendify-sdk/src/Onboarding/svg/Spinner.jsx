import { memo } from '@wordpress/element'

const Spinner = (props) => {
    const { className, ...otherProps } = props

    return (
        <svg
            className={className}
            width="20"
            height="20"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            {...otherProps}>
            <circle cx="10" cy="10" r="10" fill="black" fillOpacity="0.4" />
            <ellipse
                cx="15.5552"
                cy="6.66656"
                rx="2.22222"
                ry="2.22222"
                fill="white"
            />
        </svg>
    )
}

export default memo(Spinner)
