import { useCallback, useEffect, useState, useRef } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import classNames from 'classnames'
import { AnimatePresence, motion } from 'framer-motion'
import { getHomeTemplates } from '@onboarding/api/DataApi'
import { getThemeVariations } from '@onboarding/api/WPApi'
import { LoadingIndicator } from '@onboarding/components/LoadingIndicator'
import { SmallPreview } from '@onboarding/components/SmallPreview'
import { Title } from '@onboarding/components/Title'
import { useFetch } from '@onboarding/hooks/useFetch'
import { useIsMountedLayout } from '@onboarding/hooks/useIsMounted'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { pageState } from '@onboarding/state/factory'
import { Checkmark } from '@onboarding/svg'

export const fetcher = ({ siteType }) => getHomeTemplates(siteType)
export const fetchData = (siteType) => ({
    key: 'home-pages-list',
    siteType: siteType ?? useUserSelectionStore?.getState().siteType,
})

export const state = pageState('Layout', () => ({
    title: __('Layout', 'extendify'),
    showInSidebar: true,
    ready: false,
}))

export const HomeSelect = () => {
    const { loading, data: styleData } = useFetch(fetchData, fetcher)

    return (
        <PageLayout>
            <div className="flex-grow px-6 py-8 md:py-16 md:px-32 overflow-y-scroll">
                <Title
                    title={__('Pick a design for your website', 'extendify')}
                    description={__(
                        'You can personalize this later.',
                        'extendify',
                    )}
                />
                <div className="w-full relative max-w-6xl mx-auto">
                    {loading ? (
                        <LoadingIndicator />
                    ) : (
                        <DesignSelector styleData={styleData} />
                    )}
                </div>
            </div>
        </PageLayout>
    )
}

const DesignSelector = ({ styleData }) => {
    const { data: variations } = useFetch('variations', getThemeVariations)
    const isMounted = useIsMountedLayout()
    const [styles, setStyles] = useState([])
    const { setStyle, style: currentStyle } = useUserSelectionStore()
    const onSelect = useCallback((style) => setStyle(style), [setStyle])
    const wrapperRef = useRef()
    const once = useRef(false)

    useEffect(() => {
        state.setState({ ready: !!currentStyle?.variation?.title })
    }, [currentStyle])

    useEffect(() => {
        if (!styleData || !variations) return
        if (styles.length) return
        setStyle(null)
        ;(async () => {
            const slicedEntries = Array.from(styleData.entries())
            for (const [index, style] of slicedEntries) {
                if (!isMounted.current) return

                setStyles((styles) => [
                    ...styles,
                    {
                        ...style,
                        variation: variations[index % variations.length],
                    },
                ])

                // Delay between 350ms and 1s to make it less rigid
                const random =
                    Math.floor(Math.random() * (1000 - 150 + 1)) + 150
                await new Promise((resolve) => setTimeout(resolve, random))
            }
        })()
    }, [styleData, isMounted, variations, styles.length, setStyle])

    useEffect(() => {
        if (!currentStyle || !styles || once.current) return
        const currentButton = wrapperRef.current?.querySelector(
            `#layout-style-${currentStyle.slug} [role="button"]`,
        )
        if (!currentButton) return
        once.current = true
        currentButton.focus()
    }, [currentStyle, styles])

    return (
        <div
            className="gap-8 grid md:grid-cols-2 lg:grid-cols-3"
            data-test="layout-preview-wrapper"
            ref={wrapperRef}>
            {styles?.map((style) => (
                <div className="relative" key={style.id}>
                    <AnimatePresence>
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            duration={0.7}
                            className={classNames(
                                'relative overflow-hidden border border-gray-200 rounded cursor-pointer hover:ring-4 hover:ring-gray-300 ring-offset-2 ring-offset-white hover:outline-none focus-within:ring-4 focus-within:ring-offset-2 focus-within:ring-offset-white focus-within:ring-design-main focus-within:outline-none',
                                {
                                    'ring-4 ring-offset-2 ring-offset-white ring-design-main hover:ring-design-main':
                                        currentStyle?.id === style.id,
                                },
                            )}
                            style={{ aspectRatio: '1.55' }}>
                            <SmallPreview style={style} onSelect={onSelect} />
                        </motion.div>
                    </AnimatePresence>
                    <span aria-hidden="true">
                        {currentStyle?.id === style.id ? (
                            <Checkmark className="absolute top-0 right-0 m-2 text-design-text bg-design-main w-6 h-6 z-50 rounded-full transform translate-x-5 -translate-y-5" />
                        ) : null}
                    </span>
                </div>
            ))}
            {styleData?.slice(styles?.length).map((_, i) => (
                <AnimatePresence key={i}>
                    <motion.div
                        initial={{ opacity: 1 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        duration={0.7}
                        className="relative bg-gray-50"
                        style={{
                            aspectRatio: '1.55',
                            backgroundImage:
                                'linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.5) 50%, rgba(255,255,255,0) 100%)',
                            backgroundSize: '600% 600%',
                            animation:
                                'extendify-loading-skeleton 10s ease-in-out infinite',
                        }}
                    />
                </AnimatePresence>
            ))}
        </div>
    )
}
