import { useState, useEffect } from '@wordpress/element'
import { useTasksStore } from '@assist/state/Tasks'

export const TaskBadge = (props) => {
    const { themeSlug, launchCompleted } = window.extAssistData
    const tasks = window.extAssistData.resourceData.tasks
    const { isCompleted } = useTasksStore()
    const [taskCount, setTaskCount] = useState(
        tasks?.filter(({ slug }) => !isCompleted(slug)).length ?? 0,
    )

    useEffect(() => {
        const handle = () => {
            setTaskCount((count) => (count - 1 < 0 ? 0 : count - 1))
        }
        window.addEventListener('extendify-assist-task-completed', handle)
        return () =>
            window.removeEventListener(
                'extendify-assist-task-completed',
                handle,
            )
    }, [setTaskCount, tasks, isCompleted])

    if (themeSlug !== 'extendable' || !launchCompleted) return null
    if (taskCount === 0) return null

    return (
        <span className="awaiting-mod" {...props}>
            {taskCount > 9 ? '9' : taskCount}
        </span>
    )
}
