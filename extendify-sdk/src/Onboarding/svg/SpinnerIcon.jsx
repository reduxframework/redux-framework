import { memo } from '@wordpress/element'

const SpinnerIcon = (props) => {
    const { className, ...otherProps } = props

    return (
        <svg
            className={className}
            width="100"
            height="100"
            viewBox="0 0 100 100"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            {...otherProps}>
            <path d="M87.5 48.8281H75V51.1719H87.5V48.8281Z" fill="black" />
            <path d="M25 48.8281H12.5V51.1719H25V48.8281Z" fill="black" />
            <path d="M51.1719 75H48.8281V87.5H51.1719V75Z" fill="black" />
            <path d="M51.1719 12.5H48.8281V25H51.1719V12.5Z" fill="black" />
            <path
                d="M77.3433 75.6868L69.4082 67.7517L67.7511 69.4088L75.6862 77.344L77.3433 75.6868Z"
                fill="black"
            />
            <path
                d="M32.2457 30.5897L24.3105 22.6545L22.6534 24.3117L30.5885 32.2468L32.2457 30.5897Z"
                fill="black"
            />
            <path
                d="M77.3407 24.3131L75.6836 22.656L67.7485 30.5911L69.4056 32.2483L77.3407 24.3131Z"
                fill="black"
            />
            <path
                d="M32.2431 69.4074L30.5859 67.7502L22.6508 75.6854L24.3079 77.3425L32.2431 69.4074Z"
                fill="black"
            />
        </svg>
    )
}

export default memo(SpinnerIcon)
