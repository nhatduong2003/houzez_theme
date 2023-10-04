import { __ } from '@wordpress/i18n'
import { Title } from '@onboarding/components/Title'
import { PageLayout } from '@onboarding/layouts/PageLayout'

export const NeedsTheme = () => {
    return (
        <PageLayout includeNav={false}>
            <div className="flex-grow px-6 py-8 md:py-16 md:px-32 overflow-y-scroll">
                <Title
                    title={__('One more thing before we start.', 'extendify')}
                />
                <div className="w-full relative max-w-xl mx-auto">
                    <p className="text-base">
                        {__(
                            'Hey there, Launch is powered by Extendable and is required to proceed. You can install it from the link below and start over once activated.',
                            'extendify',
                        )}
                    </p>
                    <a
                        className="text-base text-design-main font-medium underline mt-4"
                        href={`${window.extOnbData.site}/wp-admin/theme-install.php?theme=extendable`}>
                        {__('Take me there', 'extendify')}
                    </a>
                </div>
            </div>
        </PageLayout>
    )
}
