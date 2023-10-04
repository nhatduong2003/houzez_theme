import useSWRImmutable from 'swr/immutable'
import { getSupportArticle, getSearchResults } from '@assist/api/Data'

export const useSupportArticle = (slug) => {
    const { data, error } = useSWRImmutable(
        `support-article-${slug}`,
        async () => {
            const response = await getSupportArticle(slug)

            if (!response?.data || !Array.isArray(response.data)) {
                console.error(
                    `We got an empty response while querying support-article-${slug}`,
                    response,
                )
                throw new Error('Bad Data')
            }

            return response.data?.[0] ?? {}
        },
    )
    return { data, error, loading: !data && !error }
}

export const useSearchArticles = ({ term, perPage, offset }) => {
    const { data, error } = useSWRImmutable(
        { term, perPage, offset },
        async ({ term, perPage, offset }) => {
            if (!term) return []

            const response = await getSearchResults(term, perPage, offset)

            if (!response?.data || !Array.isArray(response.data)) {
                console.error(
                    'We got an empty response while querying search-articles',
                    response,
                )
                throw new Error('Bad Data')
            }

            return response.data
        },
    )
    return { data, error, loading: !data && !error }
}
