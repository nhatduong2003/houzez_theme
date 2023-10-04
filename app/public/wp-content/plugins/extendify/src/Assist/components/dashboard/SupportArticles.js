import { __ } from '@wordpress/i18n'
import { Icon, chevronRightSmall } from '@wordpress/icons'
import { useRouter } from '@assist/hooks/useRouter'
import { useKnowledgeBaseStore } from '@assist/state/KnowledgeBase'
import { arrowTurnRight } from '@assist/svg'

export const SupportArticles = () => {
    const articles = window.extAssistData.resourceData.supportArticles
    const { navigateTo } = useRouter()
    const { pushArticle, clearArticles, reset } = useKnowledgeBaseStore()
    const userLanguage = window.extAssistData.wpLanguage

    if (articles && articles?.length === 0) {
        return (
            <div className="assist-knowledge-base-module w-full bg-white p-4 lg:p-8">
                {__('No support articles found...', 'extendify')}
            </div>
        )
    }

    return (
        <div
            id="assist-knowledge-base-module"
            className="w-full bg-white p-4 lg:p-8 text-base">
            <div className="flex justify-between items-center gap-2">
                <h3 className="text-lg leading-tight m-0 flex-1">
                    {userLanguage.startsWith('en')
                        ? __('Knowledge Base', 'extendify')
                        : __('Knowledge Base (English only)', 'extendify')}
                </h3>
                <a
                    onClick={reset}
                    href="admin.php?page=extendify-assist#knowledge-base"
                    className="inline-flex items-center no-underline hover:underline text-sm text-design-main">
                    {__('Show all', 'extendify')}
                    <Icon icon={chevronRightSmall} className="fill-current" />
                </a>
            </div>
            <div
                className="w-full mx-auto text-sm mt-4 flex flex-col gap-2"
                id="assist-knowledge-base-module-list">
                {articles.slice(0, 5).map(({ slug, extendifyTitle }) => (
                    <button
                        aria-label={extendifyTitle}
                        type="button"
                        key={slug}
                        onClick={(e) => {
                            e.preventDefault()
                            clearArticles()
                            pushArticle({ slug, title: extendifyTitle })
                            navigateTo('knowledge-base')
                        }}
                        className="flex items-center gap-2 no-underline hover:underline hover:text-design-main bg-transparent p-0 w-full cursor-pointer">
                        <Icon
                            icon={arrowTurnRight}
                            className="text-gray-600 fill-current"
                        />
                        <span className="leading-snug text-left -mt-px">
                            {extendifyTitle}
                        </span>
                    </button>
                ))}
            </div>
        </div>
    )
}
