import axios from 'axios'

export const restApi = window.extChatData.root
export const wpHeaders = {
    'Content-Type': 'application/json',
    'X-WP-Nonce': window.extChatData.nonce,
    'X-Requested-With': 'XMLHttpRequest',
    'X-Extendify-Chat': true,
    'X-Extendify': true,
}

const Axios = axios.create({
    baseURL: restApi,
    headers: wpHeaders,
})

Axios.interceptors.response.use((response) =>
    Object.prototype.hasOwnProperty.call(response, 'data')
        ? response.data
        : response,
)

export { Axios }
