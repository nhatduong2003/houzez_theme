import { useState, useEffect, useRef, useCallback } from '@wordpress/element'
import { debounce } from 'lodash'
import { useIsMounted } from '@onboarding/hooks/useIsMounted'

const originalHeights = new WeakMap()

export const usePreviewIframe = ({ container, onLoad, ready, loadDelay }) => {
    const isMounted = useIsMounted()
    const [waitForIframe, setWaitForIframe] = useState(0)
    const [iFrame, setIFrame] = useState(null)
    const [maybeOk, setMaybeOk] = useState(false)
    const isUpdating = useRef(false)

    const updateCoverBlocks = useCallback(async (frame, cntnr) => {
        const ft = frame.getBoundingClientRect().top
        const ct = cntnr.getBoundingClientRect().top
        // If they have scrolled, don't mess with it
        if (ft < ct) return

        isUpdating.current = true

        let scale = cntnr
            .querySelector('[style*="scale"]')
            ?.style?.transform?.match(/scale\((.*?)\)/)?.[1]
        scale = scale ? parseFloat(scale) : null

        const cntnrHScaled = cntnr.offsetHeight / (scale ?? 1)
        frame.style.setProperty('max-height', `${cntnrHScaled}px`, 'important')

        const coverBlocks =
            frame.contentDocument.querySelectorAll('.wp-block-cover')

        for (const el of coverBlocks) {
            if (!originalHeights.has(el)) {
                // Cache the original 'vh' value
                originalHeights.set(el, el.style.minHeight)
            }

            // Reapply the original 'vh' value so it can be used in computations
            el.style.minHeight = originalHeights.get(el)
        }

        cntnr.offsetHeight // Force a reflow
        // Give the browser time to paint
        await new Promise((resolve) => requestAnimationFrame(resolve))
        await new Promise((resolve) => requestAnimationFrame(resolve))

        for (const el of coverBlocks) {
            // Get the computed height in px and use it for your calculation
            const computedHeight = parseFloat(
                frame.contentDocument.defaultView.getComputedStyle(el).height,
            )
            el.offsetHeight // Force a reflow
            el.style.minHeight =
                computedHeight > 784 ? '784px' : computedHeight + 'px'
        }

        frame.style.setProperty('max-height', 'none', 'important')
        isUpdating.current = false
    }, [])

    useEffect(() => {
        if (!ready) return
        // continuously check for iframe
        const interval = setTimeout(() => {
            const frame = container?.querySelector('iframe[title]')
            // If not found, retry by updating state
            if (!frame) return setWaitForIframe((prev) => prev + 1)
            setIFrame(frame)
            requestAnimationFrame(() => onLoad(frame, container))
        }, 100)
        return () => clearTimeout(interval)
    }, [iFrame, ready, waitForIframe, container, onLoad])

    useEffect(() => {
        setMaybeOk(false)
        // After the iFrame is found, wait for it to load
        // Note: using load event is not reliable
        if (!iFrame) return

        const config = {
            attributes: false,
            childList: true,
            subtree: true,
        }

        // Run once in case the iframe is already loaded
        requestAnimationFrame(() => loaded(iFrame, container))
        // Last effort - =(
        setTimeout(() => loaded(), 2000)

        // Continuously check for changes
        const loaded = debounce(() => {
            if (!isMounted.current || isUpdating.current) return
            m.disconnect()
            updateCoverBlocks(iFrame, container).then(() => {
                // Reconnect observer after making changes
                setTimeout(() => setMaybeOk(true), loadDelay)
                if (isMounted.current) m.observe(iFrame.contentDocument, config)
            })
        }, 300)

        const m = new MutationObserver(loaded)
        m.observe(iFrame.contentDocument, config)

        return () => {
            loaded.cancel()
            m?.disconnect()
        }
    }, [iFrame, container, isMounted, ready, updateCoverBlocks, loadDelay])

    return { loading: !iFrame, ready: maybeOk }
}
