import { Axios as api, restApi, wpHeaders } from './axios'

// Optionally add items to request body
const denyList = ['nonce', 'api']
const extraBody = {
    ...Object.fromEntries(
        Object.entries(window.extChatData).filter(
            ([key]) => !denyList.includes(key),
        ),
    ),
}

export const getAnswer = ({ question, experienceLevel }) =>
    fetch(`${window.extChatData.api}/ask-question`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ question, experienceLevel, ...extraBody }),
    })

export const rateAnswer = ({ answerId, rating }) =>
    fetch(`${restApi}/chat/rate-answer`, {
        method: 'POST',
        headers: wpHeaders,
        body: JSON.stringify({ answerId, rating }),
    })

export const getOptions = () => api.get('chat/options')

export const updateOptions = (options) => api.post('chat/options', { options })

export const updateUserMeta = (user, option, value) =>
    api.post('draft/update-user-meta', { user, option, value })
