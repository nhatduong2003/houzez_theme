import { create } from 'zustand'
import { devtools, persist, createJSONStorage } from 'zustand/middleware'

const state = (set, get) => ({
    history: [],
    addHistory(question) {
        set((state) => ({
            history: [
                question,
                ...state.history.filter(
                    ({ questionId }) => questionId === question.questionId,
                ),
            ],
        }))
    },
    hasHistory() {
        return get().history.length > 0
    },
})

export const useHistoryStore = create(
    persist(devtools(state, { name: 'Extendify Chat History' }), {
        name: 'extendify-chat-history',
        storage: createJSONStorage(() => sessionStorage),
    }),
    state,
)
