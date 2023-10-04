import { Axios as api } from './axios'

export const updateUserMeta = (user, option, value) =>
    api.post('draft/update-user-meta', { user, option, value })
