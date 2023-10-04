import { Spinner } from '@wordpress/components'
import { sprintf, __ } from '@wordpress/i18n'
import { Icon, chevronRightSmall } from '@wordpress/icons'
import { motion, AnimatePresence } from 'framer-motion'
import { RecommendationItem } from '@assist/components/task-items/RecommendationItem'
import { useRecommendations } from '@assist/hooks/useRecommendations'
import { useRecommendationsStore } from '@assist/state/Recommendations'
import { Confetti } from '@assist/svg'

export const Recommendations = () => {
    const { recommendations, loading, error } = useRecommendations()
    const { isDismissedRecommendation } = useRecommendationsStore()

    const notDismissed = recommendations?.filter(
        (rec) => !isDismissedRecommendation(rec.slug),
    )

    if (loading || error) {
        return (
            <div className="assist-recommendations-module w-full flex justify-center bg-white border border-gray-300 p-2 lg:p-4 rounded">
                <Spinner />
            </div>
        )
    }

    return (
        <div
            id="assist-recommendations-module"
            className="w-full border border-gray-300 text-base bg-white p-4 md:p-8 rounded">
            <div className="flex justify-between items-center gap-2">
                <h2 className="text-lg leading-tight m-0 flex flex-1 items-center gap-1">
                    <span>{__('Recommendations', 'extendify')}</span>
                </h2>
                <a
                    href="admin.php?page=extendify-assist#recommendations"
                    className="inline-flex items-center no-underline text-sm text-design-main hover:underline">
                    {notDismissed?.length > 0
                        ? sprintf(
                              __('View all (%s)', 'extendify'),
                              recommendations?.length,
                          )
                        : __('View all recommendations', 'extendify')}
                    <Icon icon={chevronRightSmall} className="fill-current" />
                </a>
            </div>
            {notDismissed.length === 0 ? (
                <RecommendationsDismissed />
            ) : (
                <div
                    className="border border-b-0 border-gray-300 mt-4"
                    id="assist-recommendations-module-list">
                    <AnimatePresence>
                        {notDismissed.slice(0, 3).map((rec) => (
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
                </div>
            )}
        </div>
    )
}

const RecsItemWrapper = ({ rec, Action }) => (
    <div className="px-3 sm:px-4 py-3 flex gap-2 justify-between border-0 border-b border-gray-300 relative items-center min-h-16">
        <RecommendationItem rec={rec} Action={Action} />
    </div>
)

const RecommendationsDismissed = () => {
    return (
        <div className="flex flex-col items-center justify-center border-gray-300 p-4 lg:p-8">
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
    )
}
