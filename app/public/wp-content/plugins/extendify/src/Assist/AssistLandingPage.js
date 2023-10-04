import { SlotFillProvider } from '@wordpress/components'
import { useEffect, useState } from '@wordpress/element'
import { addAction, removeAction } from '@wordpress/hooks'
import { getPlugins } from '@wordpress/plugins'
import { SWRConfig } from 'swr'
import { Modal } from '@assist/components/Modal'
import { GuidedTour } from '@assist/components/shared/GuidedTour'
import { useRouter } from '@assist/hooks/useRouter'
import { Header } from '@assist/pages/parts/Header'
import './documentation.css'

const Page = () => {
    const { CurrentPage } = useRouter()
    const [plugins, setPlugins] = useState(getPlugins())

    useEffect(() => {
        const handler = () => setPlugins(getPlugins())
        addAction('plugins.pluginRegistered', 'extendify-assist', handler)
        addAction('plugins.pluginUnregistered', 'extendify-assist', handler)
        return () => {
            removeAction('plugins.pluginRegistered', 'extendify-assist')
            removeAction('plugins.pluginUnregistered', 'extendify-assist')
        }
    }, [])

    return (
        <SlotFillProvider>
            <Header />
            <CurrentPage />
            {plugins.map(({ name, render }) => (
                <div key={name}>{render()}</div>
            ))}
        </SlotFillProvider>
    )
}

export const AssistLandingPage = () => (
    <SWRConfig
        value={{
            onErrorRetry: (error, key, config, revalidate, { retryCount }) => {
                if (error.status === 404) return
                if (error?.data?.status === 403) {
                    // if they are logged out, we can't recover
                    window.location.reload()
                    return
                }

                // Retry after 5 seconds.
                setTimeout(() => revalidate({ retryCount }), 5000)
            },
        }}>
        <Page />
        <Modal />
        <GuidedTour />
    </SWRConfig>
)
