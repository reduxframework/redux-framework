import { Checkmark } from '@onboarding/svg'

export const CheckboxInput = ({
    label,
    slug,
    description,
    checked,
    onChange,
}) => {
    return (
        <label
            className="flex hover:text-partner-primary-bg focus-within:text-partner-primary-bg"
            htmlFor={slug}>
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
                    <span className="block pt-1">{description}</span>
                ) : (
                    <span></span>
                )}
            </span>
        </label>
    )
}
