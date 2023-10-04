import { render } from '@wordpress/element'
import { AssistAdminBarHelpCenter } from '@assist/components/global/AssistAdminBarHelpCenter'
import { AssistAdminBarHelpCenterSubMenu } from '@assist/components/global/AssistAdminBarHelpCenterSubMenu'
import { AssistAdminBarTourThisPage } from '@assist/components/global/AssistAdminBarTourThisPage'
import { GuidedTour } from '@assist/components/shared/GuidedTour'
import { TaskBadge } from '@assist/components/shared/TaskBadge'
import './app.css'

// Runs on all pages, but not everything runs on the Assist page
const init = () => {
    const q = new URLSearchParams(window.location.search)
    const launchActive = ['page'].includes(q.get('extendify-launch'))

    // Disable Assist while Launch is running
    if (launchActive) return

    const assistPage = document.getElementById('extendify-assist-landing-page')

    if (!assistPage) {
        // Assist page will load the tours separately
        const assist = Object.assign(document.createElement('div'), {
            className: 'extendify-assist',
        })
        document.body.append(assist)
        render(<GuidedTour />, assist)

        // This wont work on the assist page as tours run there separately
        const tourThisPage = Object.assign(document.createElement('li'), {
            id: 'wp-admin-bar-extendify-assist-tour-button',
            className: 'extendify-assist',
        })
        document.querySelector('#wp-admin-bar-my-account')?.after(tourThisPage)
        render(<AssistAdminBarTourThisPage />, tourThisPage)
    }

    document
        .querySelector(
            '#toplevel_page_extendify-admin-page.wp-has-current-submenu',
        )
        ?.classList.add('current')
    document
        .querySelectorAll('.extendify-assist-badge-count')
        ?.forEach((el) => render(<TaskBadge />, el))

    const helpCenter = Object.assign(document.createElement('li'), {
        id: 'wp-admin-bar-extendify-assist-help-center',
        className: 'extendify-assist menupop',
    })
    document.querySelector('#wp-admin-bar-my-account')?.after(helpCenter)
    render(<AssistAdminBarHelpCenter />, helpCenter)

    const helpCenterSubMenu = Object.assign(document.createElement('div'), {
        id: 'wp-admin-bar-extendify-assist-help-center-sub-menu',
        className: 'ab-sub-wrapper',
        style: 'margin-top: -7px',
    })
    document.querySelector('#assist-help-center')?.after(helpCenterSubMenu)
    render(<AssistAdminBarHelpCenterSubMenu />, helpCenterSubMenu)
}
init()
