import { useSelectionStore } from '@assist/state/Selections'
import { useTasksStore } from '@assist/state/Tasks'

const isAtLeastNDaysAgo = (dateString = new Date(), numDays = 0) => {
    const siteCreatedDaysAgo = Math.floor(
        (new Date() - new Date(dateString)) / (1000 * 60 * 60 * 24),
    )
    // Account for future time zones by min 0
    return Math.max(0, siteCreatedDaysAgo) >= Number(numDays)
}

// TODO: This shouldn't be a react hook
export const useRecommendations = () => {
    const { goals } = useSelectionStore()
    const { completedTasks } = useTasksStore()
    const { siteCreatedAt, resourceData } = window.extAssistData ?? {}
    const { activePlugins, recommendations } = resourceData ?? {}

    if (!Array.isArray(recommendations)) return []

    const pluginRecs =
        recommendations?.filter((rec) =>
            activePlugins?.some((plugin) =>
                rec?.pluginDepSlugs?.includes(plugin),
            ),
        ) ?? []

    // Get recommendations that match the selected goals
    const goalRecs =
        recommendations?.filter((rec) =>
            goals?.some((goal) => rec?.goalDepSlugs?.includes(goal?.slug)),
        ) ?? []

    // Get recommendations that match the selected tasks
    const taskRecs =
        recommendations?.filter((rec) =>
            completedTasks?.some((task) =>
                rec?.taskDepSlugs?.includes(task?.id),
            ),
        ) ?? []

    // Get recommendations that have general exclusions
    const generalExclusions =
        recommendations?.filter(
            ({ generalExclusions }) => generalExclusions !== null,
        ) ?? []

    // Check if the general exclusions are met, and if so, it will be returned
    const generalExclusionsChecked = generalExclusions.filter((rec) => {
        // check if sslEnabled recommendation is available and if the site is not using https
        if (
            rec.generalExclusions.includes('sslEnabled') &&
            location.protocol !== 'https:'
        ) {
            return rec
        }
    })

    // Get recommendations that have plugin exclusions
    const pluginExclusions =
        recommendations?.filter((rec) => rec?.pluginExclusions !== null) ?? []

    // If a plugin slug is added in Airtable, this recommendation will not show if the set plugin is found in the active plugins
    const pluginExclusionsChecked = pluginExclusions.filter(
        (rec) =>
            !activePlugins.some((plugin) =>
                rec?.pluginExclusions?.includes(plugin),
            ),
    )

    // Get recommendations that have no dependencies
    const recsNoDeps =
        recommendations?.filter(
            ({
                goalDepSlugs,
                taskDepSlugs,
                pluginDepSlugs,
                generalExclusions,
                pluginExclusions,
            }) =>
                goalDepSlugs === null &&
                taskDepSlugs === null &&
                pluginDepSlugs === null &&
                generalExclusions === null &&
                pluginExclusions === null,
        ) ?? []

    // Combine the filtered recommendations with the goal and task recommendations
    const recsFiltered = [
        ...recsNoDeps,
        ...pluginRecs,
        ...goalRecs,
        ...taskRecs,
        ...generalExclusionsChecked,
        ...pluginExclusionsChecked,
    ]

    const recsSorted = [...recsFiltered].sort((a, b) => b.priority - a.priority)

    return {
        recommendations: recsSorted.filter((rec) => {
            // Only show recommendations after the number of days set in rec.showAfterDay
            return isAtLeastNDaysAgo(siteCreatedAt, rec?.showAfterDay ?? 0)
                ? rec
                : false
        }),
    }
}
