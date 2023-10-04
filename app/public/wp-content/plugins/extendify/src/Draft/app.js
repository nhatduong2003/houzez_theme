import { PluginSidebar, PluginSidebarMoreMenuItem } from '@wordpress/edit-post'
import { __ } from '@wordpress/i18n'
import { registerPlugin } from '@wordpress/plugins'
import { Draft } from '@draft/Draft'
import { magic } from '@draft/svg'
import './app.css'

registerPlugin('extendify-draft', {
    render: () => (
        <>
            <PluginSidebarMoreMenuItem target="draft">
                {__('Draft', 'extendify')}
            </PluginSidebarMoreMenuItem>
            <PluginSidebar
                name="draft"
                icon={magic}
                title={__('AI Tools', 'extendify')}
                className="extendify-draft">
                <Draft />
            </PluginSidebar>
        </>
    ),
})
