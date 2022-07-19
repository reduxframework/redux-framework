import { memo } from '@wordpress/element'

const Radio = (props) => {
    const { className, ...otherProps } = props

    return (
        <svg
            className={className}
            viewBox="-4 -4 22 22"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            {...otherProps}>
            <path
                stroke="currentColor"
                d="M6.5 0.5h0s6 0 6 6v0s0 6 -6 6h0s-6 0 -6 -6v0s0 -6 6 -6"
            />
        </svg>
    )
}

export default memo(Radio)
