import { BlockPreview, transformStyles } from '@wordpress/block-editor'
import { rawHandler } from '@wordpress/blocks'
import { Spinner } from '@wordpress/components'
import {
    useRef,
    useMemo,
    useState,
    useEffect,
    useCallback,
} from '@wordpress/element'
import { forwardRef } from '@wordpress/element'
import classNames from 'classnames'
import { AnimatePresence, motion } from 'framer-motion'
import { usePreviewIframe } from '@onboarding/hooks/usePreviewIframe'
import { lowerImageQuality } from '@onboarding/lib/util'
import themeJSON from '../_data/theme-processed.json'

export const PagePreview = forwardRef(({ style }, ref) => {
    const previewContainer = useRef(null)
    const blockRef = useRef(null)
    const [ready, setReady] = useState(false)
    const transformedStyles = useMemo(
        () =>
            themeJSON?.[style?.variation?.title]
                ? transformStyles(
                      [{ css: themeJSON[style?.variation?.title] }],
                      'html body.editor-styles-wrapper',
                  )
                : null,
        [style?.variation],
    )

    const onLoad = useCallback(
        (frame) => {
            // Remove load-styles in case WP laods them
            frame.contentDocument.querySelector('[href*=load-styles]')?.remove()

            // Add variation styles
            const style = `<style id="ext-tj">
                html body.editor-styles-wrapper { background-color: var(--wp--preset--color--background) }
                ${transformedStyles}
            </style>`
            if (!frame.contentDocument?.getElementById('ext-tj')) {
                frame.contentDocument?.body?.insertAdjacentHTML(
                    'beforeend',
                    style,
                )
            }
        },
        [transformedStyles],
    )

    const { ready: show } = usePreviewIframe({
        container: ref.current,
        ready,
        onLoad,
        loadDelay: 400,
    })

    const blocks = useMemo(() => {
        const code = [style?.headerCode, style?.code, style?.footerCode]
            .filter(Boolean)
            .join('')
            .replace(
                // <!-- wp:navigation --> <!-- /wp:navigation -->
                /<!-- wp:navigation[.\S\s]*?\/wp:navigation -->/g,
                '<!-- wp:paragraph {"className":"tmp-nav"} --><p class="tmp-nav">Link | Link | Link</p ><!-- /wp:paragraph -->',
            )
            .replace(
                // <!-- wp:navigation /-->
                /<!-- wp:navigation.*\/-->/g,
                '<!-- wp:paragraph {"className":"tmp-nav"} --><p class="tmp-nav">Link | Link | Link</p ><!-- /wp:paragraph -->',
            )
            .replace(
                /<!-- wp:site-logo.*\/-->/g,
                '<!-- wp:paragraph {"className":"custom-logo"} --><img class="custom-logo" style="height: 40px;" src="https://assets.extendify.com/demo-content/logos/extendify-demo-logo.png"><!-- /wp:paragraph -->',
            )
        return rawHandler({ HTML: lowerImageQuality(code) })
    }, [style])

    useEffect(() => {
        setReady(false)
        const timer = setTimeout(() => setReady(true), 0)
        return () => clearTimeout(timer)
    }, [blocks])

    return (
        <>
            <AnimatePresence>
                {show || (
                    <motion.div
                        initial={{ opacity: 0.7 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        transition={{ duration: 0.3 }}
                        className="absolute inset-0 z-30 pointer-events-none"
                        style={{
                            backgroundColor: 'rgba(204, 204, 204, 0.25)',
                            backgroundImage:
                                'linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.5) 50%, rgba(255,255,255,0) 100%)',
                            backgroundSize: '600% 600%',
                            animation:
                                'extendify-loading-skeleton 10s ease-in-out infinite',
                        }}>
                        <div className="absolute inset-0 flex items-center justify-center">
                            <Spinner className="w-10 h-10 text-design-main" />
                        </div>
                    </motion.div>
                )}
            </AnimatePresence>
            <div
                data-test="layout-preview"
                ref={blockRef}
                className={classNames('group w-full bg-transparent z-10', {
                    'opacity-0': !show,
                })}>
                <div ref={previewContainer} className="relative rounded-lg">
                    <BlockPreview
                        blocks={blocks}
                        viewportWidth={1400}
                        additionalStyles={[
                            // TODO { css: themeJSON[style?.variation?.title] },
                            {
                                css: '.rich-text [data-rich-text-placeholder]:after { content: "" }',
                            },
                        ]}
                    />
                </div>
            </div>
        </>
    )
})
