import { createSlotFill } from '@wordpress/components'
import { useEffect, useState } from '@wordpress/element'
import { registerPlugin, unregisterPlugin } from '@wordpress/plugins'
import { ChatSettings } from '@chat/ChatSettings'
import { ChatButton } from '@chat/components/ChatButton'
import { ChatDialog } from '@chat/components/ChatDialog'
import { motion, AnimatePresence } from 'framer-motion'

export const Chat = () => {
    const [showChat, setShowChat] = useState(window.extChatData?.showChat)
    const [showDialog, setShowDialog] = useState(false)

    useEffect(() => {
        const { Fill } = createSlotFill('Extendify/Assist/Settings')
        registerPlugin('extendify-chat-settings', {
            render: () => (
                <Fill>
                    <ChatSettings
                        showChat={showChat}
                        setShowChat={setShowChat}
                    />
                </Fill>
            ),
        })
        return () => unregisterPlugin('extendify-chat-settings')
    }, [showChat])

    if (!showChat) return null

    return (
        <>
            <ChatButton
                showDialog={showDialog}
                onClick={() => setShowDialog(!showDialog)}
            />
            <AnimatePresence>
                {showDialog && (
                    <motion.div
                        key="chat-dialog"
                        className="fixed bottom-0 right-0 z-high"
                        initial={{ y: 15, opacity: 0 }}
                        exit={{ y: 15, opacity: 0 }}
                        transition={{
                            y: { duration: 0.25 },
                            opacity: { duration: 0.1 },
                        }}
                        animate={{ y: 0, opacity: 1 }}>
                        <ChatDialog setShowChat={setShowChat} />
                    </motion.div>
                )}
            </AnimatePresence>
        </>
    )
}
