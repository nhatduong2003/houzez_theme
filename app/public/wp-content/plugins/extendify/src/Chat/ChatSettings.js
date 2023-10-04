import { Spinner, ToggleControl } from '@wordpress/components'
import { useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { updateOptions } from '@chat/api/Data'

export const ChatSettings = ({ showChat, setShowChat }) => {
    const [loading, setLoading] = useState(false)

    const toggleChat = () => {
        setLoading(true)
        updateOptions({ showChat: !showChat }).then(() => {
            setShowChat(!showChat)
            setLoading(false)
        })
    }

    return (
        <div className="flex items-center mt-4">
            <ToggleControl
                label={__('Enable the AI Chatbot', 'extendify')}
                checked={showChat}
                onChange={toggleChat}
                className="m-0 focus:ring-2 focus:ring-design-main focus:border-design-main"
            />
            {loading && <Spinner className="mt-0 text-design-main" />}
        </div>
    )
}
