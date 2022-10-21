export const CheckboxInputCard = ({
    label,
    slug,
    description,
    checked,
    onChange,
    Icon,
}) => {
    return (
        <label
            className="w-full flex items-center justify-between text-gray-900 font-semibold p-4 h-full"
            htmlFor={slug}>
            <div className="flex items-center flex-auto">
                <span className="w-5 h-5 relative inline-block mr-3 align-middle">
                    <input
                        id={slug}
                        className="h-5 w-5 rounded-sm m-0"
                        type="checkbox"
                        onChange={onChange}
                        defaultChecked={checked}
                    />
                    <svg
                        className="absolute block h-5 inset-0 w-5 text-white"
                        viewBox="1 0 20 20"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        role="presentation">
                        <path
                            d="M8.72912 13.7449L5.77536 10.7911L4.76953 11.7899L8.72912 15.7495L17.2291 7.24948L16.2304 6.25073L8.72912 13.7449Z"
                            fill="currentColor"
                        />
                    </svg>
                </span>
                <span>
                    <span className="text-base">{label}</span>
                    {description ? (
                        <span className="block pt-1 text-gray-700 pr-4 font-normal">
                            {description}
                        </span>
                    ) : (
                        <span></span>
                    )}
                </span>
            </div>
            {Icon && (
                <Icon className="flex-none text-partner-primary-bg h-6 w-6" />
            )}
        </label>
    )
}
