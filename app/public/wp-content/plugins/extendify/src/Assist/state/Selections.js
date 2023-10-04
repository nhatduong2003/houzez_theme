import { create } from 'zustand'
import { devtools, persist, createJSONStorage } from 'zustand/middleware'
import { getUserSelectionData, saveUserSelectionData } from '@assist/api/Data'

const key = `extendify-site-selection-${window.extAssistData.siteId}`
const startingState = {
    siteType: {},
    siteInformation: {
        title: undefined,
    },
    siteTypeSearch: [],
    style: null,
    pages: [],
    plugins: [],
    goals: [],
    // initialize the state with default values
    ...((window.extAssistData.userData.userSelectionData?.data || {})?.state ??
        {}),
}

const state = () => ({
    ...startingState,
    // Add methods here
})

const storage = {
    getItem: async () => JSON.stringify(await getUserSelectionData()),
    setItem: async (_, value) => await saveUserSelectionData(value),
    removeItem: () => undefined,
}

export const useSelectionStore = create(
    persist(devtools(state, { name: 'Extendify User Selections' }), {
        name: key,
        storage: createJSONStorage(() => storage),
        skipHydration: true,
    }),
    state,
)
