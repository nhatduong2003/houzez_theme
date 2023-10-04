import { Dropdown } from '@wordpress/components'
import { __ } from '@wordpress/i18n'
import { Icon, moreVertical } from '@wordpress/icons'
import { useRecommendationsStore } from '@assist/state/Recommendations'

export const RecommendationItem = ({ rec }) => {
    const { track, isDismissedRecommendation, dismissRecommendation } =
        useRecommendationsStore()
    return (
        <>
            <div key={rec.slug} className="w-full p-2 lg:p-4 relative">
                <div className="flex flex-col">
                    <h3 className="m-0 mb-2 text-lg">{rec.title}</h3>
                    <p className="m-0 text-sm">{rec.description}</p>
                    <a
                        className="px-3 py-2 mt-4 w-max leading-tight min-w-30 button-focus bg-gray-100 hover:bg-gray-200 focus:shadow-button text-gray-900 rounded relative cursor-pointer text-center no-underline text-sm"
                        href={
                            rec.linkType === 'externalLink'
                                ? `${rec.externalLink}`
                                : `${window.extAssistData.adminUrl}${rec.internalLink}`
                        }
                        onClick={() => track(rec.slug)}
                        target={rec.linkType === 'externalLink' ? '_blank' : ''}
                        rel={
                            rec.linkType === 'externalLink'
                                ? 'noreferrer'
                                : undefined
                        }>
                        <span>{rec.buttonText}</span>
                    </a>
                </div>

                {isDismissedRecommendation(rec.slug) ? (
                    <div className="w-5" />
                ) : (
                    <Dropdown
                        className="w-5 absolute top-0 right-0 m-2 lg:m-4"
                        position="bottom left"
                        popoverProps={{ placement: 'bottom-end' }}
                        renderContent={({ onClose }) => (
                            <button
                                onClick={() => {
                                    onClose()
                                    dismissRecommendation(rec.slug)
                                }}
                                type="button"
                                className="-m-2 p-2 px-4 text-gray-900 text-sm border-0 cursor-pointer rounded-none bg-white hover:bg-gray-100 text-center no-underline">
                                {__('Dismiss', 'extendify')}
                            </button>
                        )}
                        renderToggle={({ onToggle }) => (
                            <button
                                onClick={onToggle}
                                type="button"
                                className="p-0 text-white text-xs border-0 rounded cursor-pointer bg-transparent text-center no-underline relative">
                                <Icon icon={moreVertical} className="" />
                            </button>
                        )}
                    />
                )}
            </div>
        </>
    )
}
