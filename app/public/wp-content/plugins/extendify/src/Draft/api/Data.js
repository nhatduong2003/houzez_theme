import { __ } from '@wordpress/i18n'

const DRAFT_URL = 'https://ai.extendify.com/api/draft'
// const DRAFT_URL = 'http://localhost:3000/api/draft'

export const completion = async (prompt, promptType, systemMessageKey) => {
    const response = await fetch(`${DRAFT_URL}/completion`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ prompt, promptType, systemMessageKey }),
    })

    if (!response.ok) {
        if (response.status === 429) {
            throw new Error(__('Service temporarily unavailable', 'extendify'))
        }
        throw new Error(`Server error: ${response.status}`)
    }

    return response
}
