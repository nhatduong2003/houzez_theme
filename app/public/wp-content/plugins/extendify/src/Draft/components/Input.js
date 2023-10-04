import { Spinner } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { arrowRight, Icon } from '@wordpress/icons'
import { DynamicTextarea } from '@draft/components/DynamicTextarea'
import { magic } from '@draft/svg'
import classnames from 'classnames'

export const Input = ({
    inputText,
    setInputText,
    ready,
    setReady,
    setPrompt,
    loading,
}) => {
    const submit = (event) => {
        event.preventDefault()

        if (!ready || loading) return

        setPrompt({
            text: inputText,
            promptType: 'create',
            systemMessageKey: 'generate',
        })
        setInputText('')
        setReady(false)
    }

    return (
        <form className="relative flex items-start" onSubmit={submit}>
            <Icon
                icon={magic}
                className="text-design-main fill-current w-5 h-5 absolute left-2 top-3"
            />
            <DynamicTextarea
                disabled={loading}
                placeholder={
                    loading
                        ? __('AI is writing...', 'extendify')
                        : __('Ask AI to generate text', 'extendify')
                }
                value={inputText}
                className="bg-transparent border-none shadow-none w-full h-full px-10 py-3 overflow-hidden resize-none"
                onChange={(event) => {
                    setInputText(event.target.value)
                    setReady(event.target.value.length > 0)
                }}
                onKeyDown={(event) => {
                    if (event.key === 'Enter' && !event.shiftKey) {
                        event.preventDefault()
                        submit(event)
                    }
                }}
            />
            {loading && (
                <div className="text-gray-700 absolute right-4 w-4 h-4 p-1 mt-2.5">
                    <Spinner style={{ margin: '0' }} />
                </div>
            )}
            {!loading && (
                <button
                    type="submit"
                    disabled={!ready}
                    aria-label={__('Submit', 'extendify')}
                    className={classnames(
                        'bg-transparent border-none absolute right-2 p-0 mt-2.5',
                        {
                            'cursor-pointer text-gray-700 hover:text-design-main':
                                ready,
                            'text-gray-500': !ready,
                        },
                    )}>
                    <Icon
                        icon={arrowRight}
                        onClick={submit}
                        className="fill-current w-6 h-6"
                    />
                </button>
            )}
        </form>
    )
}
