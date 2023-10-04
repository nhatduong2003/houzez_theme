import { useState, useLayoutEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { colord } from 'colord'
import { Logo } from '@onboarding/svg'
import { useRouter } from '@assist/hooks/useRouter'
import { Nav } from '@assist/pages/parts/Nav'

export const Header = () => {
    const { filteredPages, navigateTo, current } = useRouter()
    const [menuOpen, setMenuOpen] = useState(false)
    const [contrastBg, setContrastBg] = useState()
    const [focusColor, setFocusColor] = useState()

    useLayoutEffect(() => {
        const documentStyles = window.getComputedStyle(document.body)
        const bannerMain = documentStyles.getPropertyValue('--ext-banner-main')
        const b = colord(bannerMain || '#000000')
        const contrast = b.isDark() ? b.lighten(0.1) : b.darken(0.1)
        setContrastBg(contrast.toHex())
        const focus = b.isDark() ? b.lighten(0.3) : b.darken(0.3)
        setFocusColor(focus.toHex())
    }, [])

    return (
        <header className="w-full flex bg-banner-main">
            <div className="max-w-screen-2xl w-full mx-4 md:mx-12 3xl:mx-auto mt-auto flex flex-col">
                <div className="flex flex-wrap justify-between items-center my-6 xl:my-8 gap-x-4 gap-y-2">
                    {window.extAssistData?.partnerLogo && (
                        <div className="w-40 h-16 flex items-center">
                            <a
                                href={`${window.extAssistData.adminUrl}admin.php?page=extendify-assist`}>
                                <img
                                    className="h-full w-full"
                                    src={window.extAssistData.partnerLogo}
                                    alt={window.extAssistData.partnerName}
                                />
                            </a>
                        </div>
                    )}
                    {!window.extAssistData?.partnerLogo && (
                        <a
                            href={`${window.extAssistData.adminUrl}admin.php?page=extendify-assist`}>
                            <Logo className="logo text-banner-text w-32 sm:w-40" />
                        </a>
                    )}
                    <div className="lg:hidden">
                        <button
                            type="button"
                            className={classNames(
                                'cursor-pointer bg-transparent hover:bg-white hover:bg-opacity-20 text-banner-text h-8 rounded-sm flex items-center gap-2 text-base',
                                { 'bg-white bg-opacity-20': menuOpen },
                            )}
                            onClick={() => setMenuOpen((v) => !v)}>
                            <span className="dashicons dashicons-menu-alt text-banner-text" />
                            {__('Menu', 'extendify')}
                        </button>
                    </div>
                    <div
                        id="assist-menu-bar"
                        className={classNames(
                            'lg:flex lg:w-auto flex-wrap gap-4 items-center',
                            {
                                hidden: !menuOpen,
                                block: menuOpen,
                                'w-full': menuOpen,
                            },
                        )}>
                        <Nav
                            hideMenu={() => setMenuOpen(false)}
                            pages={filteredPages}
                            activePage={current?.slug}
                            setActivePage={navigateTo}
                        />
                        <a
                            style={{
                                borderColor: contrastBg,
                                '--tw-ring-color': focusColor,
                                '--ext-override': focusColor,
                            }}
                            className="text-sm text-center bg-design-main text-design-text border cursor-pointer rounded-b-sm lg:rounded-sm py-2 px-3 no-underline block lg:inline-block hover:border-override hover:bg-transparent hover:text-banner-text focus:ring-wp focus:ring-offset-1 focus:ring-offset-banner-main focus:outline-none transition-colors duration-200"
                            href={window.extAssistData.home}
                            target="_blank"
                            rel="noreferrer">
                            {__('View site', 'extendify')}
                        </a>
                    </div>
                </div>
            </div>
        </header>
    )
}
