import useSWRImmutable from 'swr/immutable'
import { PATTERNS_HOST } from '../../constants'

export const useSiteTypes = (search) => {
    const { data, error } = useSWRImmutable(search || 'cold-boot', async () => {
        const { wpLanguage } = window.extOnbData
        const now = performance.now()
        const url = new URL(`${PATTERNS_HOST}/api/site-types`)
        search && url.searchParams.append('search', search)
        search || url.searchParams.append('boot', 'true')
        wpLanguage && url.searchParams.append('lang', wpLanguage)

        const res = await fetch(url.toString(), {
            headers: {
                'Content-Type': 'application/json',
            },
        })
        if (!search) return undefined
        if (!res.ok) throw new Error('Bad response from server')
        const siteTypes = await res.json()
        if (!Array.isArray(siteTypes)) {
            throw new Error('Bad response from server')
        }
        return {
            siteTypes,
            time: ((performance.now() - now) / 1000).toFixed(4),
        }
    })

    return { data, error, loading: !data && !error }
}
