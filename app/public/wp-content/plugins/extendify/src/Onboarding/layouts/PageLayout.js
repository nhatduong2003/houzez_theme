import { PageControl } from '@onboarding/components/PageControl'
import { Logo } from '@onboarding/svg'

export const PageLayout = ({ children, includeNav = true }) => {
    return (
        <div className="flex flex-col h-screen">
            <div className="flex-none px-6 py-5 md:px-12 md:py-6 w-full bg-banner-main">
                {window.extOnbData?.partnerLogo ? (
                    <img
                        className="w-auto h-8"
                        src={window.extOnbData.partnerLogo}
                        alt={window.extOnbData?.partnerName ?? ''}
                    />
                ) : (
                    <Logo className="text-banner-text w-auto h-8" />
                )}
            </div>
            {children}
            {includeNav && (
                <div className="flex-none px-6 py-5 md:px-12 md:py-6 w-full bg-white shadow-surface border-t border-gray-100">
                    <PageControl />
                </div>
            )}
        </div>
    )
}
