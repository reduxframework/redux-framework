import { Checkmark } from '@onboarding/svg'

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
            className="w-full flex items-center justify-between hover:text-partner-primary-bg focus-within:text-partner-primary-bg font-semibold p-4"
            htmlFor={slug}>
            <div className="flex items-center flex-auto">
                <span className="mt-0.5 w-6 h-6 relative inline-block mr-3 align-middle">
                    <input
                        id={slug}
                        className="h-5 w-5 rounded-sm"
                        type="checkbox"
                        onChange={onChange}
                        defaultChecked={checked}
                    />
                    <Checkmark
                        className="absolute components-checkbox-control__checked"
                        style={{ width: 24, color: '#fff' }}
                        role="presentation"
                    />
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
