import { subscribe } from '@wordpress/data'
import { render } from '@wordpress/element'
import { CtaButton, MainButtonWrapper } from '@library/components/MainButtons'

const userState = window.extendifyData?.user?.state
const isAdmin = () => window.extendifyData.user === null || userState?.isAdmin

// Add the MAIN button when Gutenberg is available and ready
subscribe(() => {
    requestAnimationFrame(() => {
        if (document.getElementById('extendify-templates-inserter')) {
            return
        }
        if (
            !document.querySelector('.edit-post-header-toolbar') &&
            !document.querySelector('.edit-site-header-edit-mode__start') // FSE
        ) {
            return
        }
        const buttonContainer = Object.assign(document.createElement('div'), {
            id: 'extendify-templates-inserter',
        })
        // Standard block editor
        document
            .querySelector('.edit-post-header-toolbar')
            ?.append(buttonContainer)
        // FSE block editor
        document
            .querySelector('.edit-site-header-edit-mode__start')
            ?.append(buttonContainer)

        render(<MainButtonWrapper />, buttonContainer)
    })
})

// The CTA button inside patterns
subscribe(() => {
    requestAnimationFrame(() => {
        if (!isAdmin()) return
        if (!document.querySelector('[id$=patterns-view]')) return
        if (document.getElementById('extendify-cta-button')) return

        const ctaButtonContainer = Object.assign(
            document.createElement('div'),
            { id: 'extendify-cta-button-container' },
        )

        document
            .querySelector('[id$=patterns-view]')
            .prepend(ctaButtonContainer)
        render(<CtaButton />, ctaButtonContainer)
    })
})
