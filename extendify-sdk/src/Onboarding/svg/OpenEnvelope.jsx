import { memo } from '@wordpress/element'

const OpenEnvelope = (props) => {
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
                d="M12 14L3 9V19H21V9L12 14Z"
                fill="currentColor"
            />
            <path
                d="M21.008 6.24719L12 0.992188L2.992 6.24719C2.38 6.60419 2 7.26619 2 7.97519V18.0002C2 19.1032 2.897 20.0002 4 20.0002H20C21.103 20.0002 22 19.1032 22 18.0002V7.97519C22 7.26619 21.62 6.60419 21.008 6.24719ZM19.892 7.91219L12 12.8222L4.108 7.91119L12 3.30819L19.892 7.91219ZM4 18.0002V10.2002L12 15.1782L20 10.2002L20.001 18.0002H4Z"
                fill="currentColor"
            />
        </svg>
    )
}

export default memo(OpenEnvelope)
