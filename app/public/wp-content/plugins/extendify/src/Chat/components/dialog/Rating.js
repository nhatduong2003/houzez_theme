import { useState, useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Icon } from '@wordpress/icons'
import { rateAnswer } from '@chat/api/Data'
import { thumbUp, thumbDown } from '@chat/svg'
import classnames from 'classnames'

export const Rating = ({ answerId }) => {
    const [rating, setRating] = useState(undefined)

    useEffect(() => {
        if (!answerId) return
        if (rating === undefined) return
        rateAnswer({ answerId, rating })
    }, [rating, answerId])

    return (
        <div className="mt-1 flex items-center gap-0.5 justify-end text-right">
            <button
                type="button"
                aria-pressed={rating === 1}
                aria-live="polite"
                onClick={() => setRating((current) => (current === 1 ? 0 : 1))}
                aria-label={
                    rating === 1
                        ? __('Remove rating', 'extendify')
                        : __('Rate that this answer was helpful', 'extendify')
                }
                className={classnames(
                    'cursor-pointer bg-transparent w-5 h-5 text-gray-500 border-0 p-0 m-0 hover:text-design-main',
                    {
                        'text-design-main': rating === 1,
                    },
                )}>
                <Icon className="fill-current" icon={thumbUp} />
            </button>

            <button
                type="button"
                aria-pressed={rating === -1}
                aria-live="polite"
                onClick={() =>
                    setRating((current) => (current === -1 ? 0 : -1))
                }
                aria-label={
                    rating === -1
                        ? __('Remove rating', 'extendify')
                        : __(
                              'Rate that this answer was not helpful',
                              'extendify',
                          )
                }
                className={classnames(
                    'cursor-pointer bg-transparent w-5 h-5 text-gray-500 border-0 p-0 m-0 hover:text-design-main',
                    {
                        'text-design-main': rating === -1,
                    },
                )}>
                <Icon className="fill-current" icon={thumbDown} />
            </button>
        </div>
    )
}
