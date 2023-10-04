import { pingServer } from '@onboarding/api/DataApi'

/** Removes any hash or qs values from URL - Airtable adds timestamps */
export const stripUrlParams = (url) => url?.[0]?.url?.split(/[?#]/)?.[0]

export const lowerImageQuality = (html) => {
    return html.replace(
        /(https?:\/\/\S+\?w=\d+)/gi,
        '$1&q=10&auto=format,compress&fm=avif',
    )
}

/**
 * Will ping every 1s until we get a 200 response from the server.
 * This is used because we were dealing with a particular issue where
 * servers we're very resource limited and rate limiting was common.
 * */
export const waitFor200Response = async () => {
    try {
        // This will error if not 200
        await pingServer()
        return true
    } catch (error) {
        //
    }
    await new Promise((resolve) => setTimeout(resolve, 1000))
    return waitFor200Response()
}
