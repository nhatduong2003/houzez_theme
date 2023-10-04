import { useLayoutEffect, useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Icon } from '@wordpress/icons'
import { colord } from 'colord'

export const Nav = ({ pages, activePage, setActivePage, hideMenu }) => {
    const [activeBg, setActiveBg] = useState()
    const [focusColor, setFocusColor] = useState()

    useLayoutEffect(() => {
        const documentStyles = window.getComputedStyle(document.body)
        const bannerMain = documentStyles.getPropertyValue('--ext-banner-main')
        const b = colord(bannerMain || '#000000')
        const active = b.isDark() ? b.lighten(0.1) : b.darken(0.1)
        setActiveBg(active.toHex())
        const focus = b.isDark() ? b.lighten(0.3) : b.darken(0.3)
        setFocusColor(focus.toHex())
    }, [])

    return (
        <nav aria-labelledby="assist-landing-nav">
            <h2 id="assist-landing-nav" className="sr-only">
                {__('Assist navigation', 'extendify')}
            </h2>
            <ul className="lg:space-x-1 flex flex-wrap lg:flex-nowrap rounded-t-sm overflow-hidden m-0 p-0 px-1 pb-2 lg:pb-0 lg:gap-1.5 bg-white bg-opacity-5 lg:bg-transparent">
                {pages.map((page) => (
                    <li
                        className="list-none m-0 p-0 py-1 w-full lg:w-auto"
                        key={page.slug}>
                        <button
                            onClick={() => {
                                setActivePage(page.slug)
                                hideMenu()
                            }}
                            type="button"
                            aria-current={activePage === page.slug}
                            style={{
                                '--tw-ring-color': focusColor,
                                '--ext-override': activeBg,
                                background:
                                    activePage === page.slug
                                        ? activeBg
                                        : 'transparent',
                            }}
                            className="rounded-sm w-full px-3 lg:px-2 lg:pr-3 py-2 text-sm text-banner-text whitespace-nowrap cursor-pointer flex gap-1.5 items-center hover:bg-override focus:ring-wp focus:ring-offset-1 focus:ring-offset-banner-main focus:outline-none transition-colors duration-200">
                            {page.icon && (
                                <Icon
                                    icon={page.icon}
                                    className="fill-current flex"
                                />
                            )}
                            {page.name}
                        </button>
                    </li>
                ))}
            </ul>
        </nav>
    )
}
