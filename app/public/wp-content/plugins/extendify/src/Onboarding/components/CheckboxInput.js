import classNames from 'classnames'

export const CheckboxInput = ({
    label,
    slug,
    description,
    checked,
    onChange,
    locked = false,
}) => {
    return (
        <label
            className={classNames('flex items-center py-3.5 px-4', {
                'cursor-not-allowed': locked,
                'hover:text-design-main focus-within:text-design-mains':
                    !locked,
            })}
            htmlFor={slug}>
            <span className="w-5 h-5 relative inline-block mr-3 align-middle">
                <input
                    id={slug}
                    className="h-5 w-5 rounded-sm m-0"
                    style={{
                        '--ext-design-main': locked ? '#BBBBBB' : undefined,
                    }}
                    disabled={locked}
                    type="checkbox"
                    onChange={locked ? undefined : onChange}
                    checked={locked ? true : checked}
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
            <span className="flex flex-col flex-grow overflow-hidden">
                <span className="text-base font-medium leading-tight truncate">
                    {label}
                </span>
                {description ? (
                    <span className="block pt-1">{description}</span>
                ) : (
                    <span />
                )}
            </span>
        </label>
    )
}
