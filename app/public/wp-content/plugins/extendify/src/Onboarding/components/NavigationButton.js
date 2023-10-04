import classnames from 'classnames'

export const NavigationButton = (props) => {
    return (
        <button
            {...props}
            className={classnames(
                'rounded flex items-center px-6 py-3 leading-6 button-focus border gap-2',
                {
                    'opacity-50 cursor-not-allowed': props.disabled,
                },
                props.className,
            )}
            type="button">
            {props.children}
        </button>
    )
}
