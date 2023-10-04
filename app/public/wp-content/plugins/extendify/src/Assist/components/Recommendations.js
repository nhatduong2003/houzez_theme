import { useState } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import { motion, AnimatePresence } from 'framer-motion'
import { RecommendationItem } from '@assist/components/task-items/RecommendationItem'
import { useRecommendations } from '@assist/hooks/useRecommendations'
import { useRecommendationsStore } from '@assist/state/Recommendations'
import { Confetti } from '@assist/svg'

export const Recommendations = () => {
    const { recommendations } = useRecommendations()
    const { isDismissedRecommendation } = useRecommendationsStore()
    const [showDismissed, setShowDismissed] = useState(false)

    // Now filter all tasks that are marked as dismissed
    const dismissed = recommendations?.filter((rec) =>
        isDismissedRecommendation(rec.slug),
    )
    // Now filter all tasks that are not dismissed yet
    const notDismissed = recommendations?.filter(
        (rec) => !isDismissedRecommendation(rec.slug),
    )
    // Toggle show/hide completed tasks
    const toggleDismissedTasks = () => {
        if (showDismissed) {
            setShowDismissed(false)
            return
        }
        setShowDismissed(true)
    }

    return (
        <div className="my-4 w-full bg-white border border-gray-300 p-4 lg:p-8 rounded">
            <div className="mb-6 flex gap-0 flex-col">
                <h2 className="my-0 text-lg">
                    {__(
                        'Personalized recommendations for your site',
                        'extendify',
                    )}
                </h2>
                <div className="flex gap-1">
                    <span>
                        {sprintf(
                            // translators: %s is the number of tasks
                            __('%s dismissed', 'extendify'),
                            dismissed.length,
                        )}
                    </span>
                    {dismissed.length > 0 && (
                        <>
                            <span>&middot;</span>
                            <button
                                type="button"
                                className="underline cursor-pointer p-0 bg-white"
                                onClick={toggleDismissedTasks}>
                                {showDismissed
                                    ? __('Hide', 'extendify')
                                    : __('Show', 'extendify')}
                            </button>
                        </>
                    )}
                </div>
            </div>
            <div
                className="not-dismissed-recs w-full border border-b-0 border-gray-300"
                data-test="not-dismissed-recs">
                {showDismissed ? (
                    notDismissed.map((rec) => (
                        <RecsItemWrapper key={rec.slug} rec={rec} />
                    ))
                ) : notDismissed.length === 0 ? (
                    <div className="flex flex-col items-center justify-center border-b border-gray-300 p-2 lg:p-8">
                        <Confetti aria-hidden={true} />
                        <p className="mb-0 text-lg font-bold">
                            {__('All caught up!', 'extendify')}
                        </p>
                        <p className="mb-0 text-sm">
                            {__(
                                'Congratulations! Take a moment to celebrate.',
                                'extendify',
                            )}
                        </p>
                    </div>
                ) : (
                    <AnimatePresence>
                        {notDismissed.map((rec) => (
                            <motion.div
                                key={rec.slug}
                                variants={{
                                    fade: {
                                        opacity: 0,
                                        x: 15,
                                        transition: {
                                            duration: 0.5,
                                        },
                                    },
                                    shrink: {
                                        height: 0,
                                        transition: {
                                            delay: 0.5,
                                            duration: 0.2,
                                        },
                                    },
                                }}
                                exit={['fade', 'shrink']}>
                                <RecsItemWrapper rec={rec} />
                            </motion.div>
                        ))}
                    </AnimatePresence>
                )}
            </div>
            {showDismissed && (
                <div className="dismissed-tasks w-full border border-b-0 border-t-0 border-gray-300">
                    {dismissed.map((rec) => (
                        <RecsItemWrapper key={rec.slug} rec={rec} />
                    ))}
                </div>
            )}
        </div>
    )
}

const RecsItemWrapper = ({ rec }) => (
    <div className="px-3 sm:px-4 py-3 flex gap-2 justify-between border-0 border-b border-gray-300 relative items-center min-h-16">
        <RecommendationItem rec={rec} />
    </div>
)
