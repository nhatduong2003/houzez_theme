import { render } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { AssistLandingPage } from '@assist/AssistLandingPage'
import './app.css'

const init = () => {
    const assistPage = document.getElementById('extendify-assist-landing-page')
    if (!assistPage) return
    // append skip link to get here
    document
        .querySelector('.screen-reader-shortcut')
        .insertAdjacentHTML(
            'afterend',
            `<a href="#extendify-assist-landing-page" class="screen-reader-shortcut">${__(
                'Skip to Assist',
                'extendify',
            )}</a>`,
        )
    render(<AssistLandingPage />, assistPage)
}
init()
