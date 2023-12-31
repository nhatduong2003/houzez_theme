import { __ } from '@wordpress/i18n'
import { Icon } from '@wordpress/icons'
import { chevron, robot } from '@chat/svg'

export const ChatButton = ({ showDialog, onClick }) => {
    return (
        <div className="fixed bottom-6 right-6 rounded-full overflow-hidden">
            <button
                type="button"
                className="extendify-chat-button outline-none border-none bg-design-main flex items-center p-3 cursor-pointer"
                aria-label={__('AI Chat', 'extendify')}
                onClick={onClick}>
                <Icon
                    icon={showDialog ? chevron : robot}
                    className="text-design-text fill-current w-6 h-6"
                />
            </button>
        </div>
    )
}
