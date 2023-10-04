import { useState, useRef, useEffect, useMemo } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { getPageTemplates } from '@onboarding/api/DataApi'
import { PagePreview } from '@onboarding/components/PagePreview'
import { PageSelectButton } from '@onboarding/components/PageSelectButton'
import { Title } from '@onboarding/components/Title'
import { useFetch } from '@onboarding/hooks/useFetch'
import { PageLayout } from '@onboarding/layouts/PageLayout'
import { useUserSelectionStore } from '@onboarding/state/UserSelections'
import { pageState } from '@onboarding/state/factory'

export const fetcher = ({ siteType }) => getPageTemplates(siteType)
export const fetchData = (siteType) => ({
    key: 'pages-list',
    siteType: siteType ?? useUserSelectionStore?.getState().siteType,
})

export const state = pageState('Pages', () => ({
    title: __('Pages', 'extendify'),
    showInSidebar: true,
    ready: true,
}))

export const PagesSelect = () => {
    const { data: availablePages, loading } = useFetch(fetchData, fetcher)
    const [previewing, setPreviewing] = useState()
    const [expandMore, setExpandMore] = useState()
    const { pages, remove, add, has, style } = useUserSelectionStore()
    const pagePreviewRef = useRef()

    const homePage = useMemo(
        () => ({
            id: 'home-page',
            slug: 'home-page',
            name: __('Home page', 'extendify'),
            patterns: style?.code.map((code, i) => ({
                name: `pattern-${i}`,
                code,
            })),
        }),
        [style],
    )
    const styleMemo = useMemo(
        () => ({
            ...style,
            code: previewing
                ? previewing.patterns.map(({ code }) => code).join('')
                : '',
        }),
        [style, previewing],
    )

    const handlePageToggle = (page) => {
        if (has('pages', page)) {
            remove('pages', page)
            return
        }
        add('pages', page)
        return setPreviewing(page)
    }

    useEffect(() => {
        // This needs two frames before the code is rendered
        let raf2
        const id = requestAnimationFrame(() => {
            raf2 = requestAnimationFrame(() => {
                pagePreviewRef?.current?.scrollTo(0, 0)
            })
        })
        return () => {
            cancelAnimationFrame(id)
            cancelAnimationFrame(raf2)
        }
    }, [previewing])

    useEffect(() => {
        if (previewing) return
        setPreviewing(homePage)
    }, [previewing, homePage])

    useEffect(() => {
        // If no pages have been set, then add the recommended pages
        if (pages) return
        if (!availablePages?.recommended) return
        availablePages.recommended.forEach((page) => add('pages', page))
    }, [pages, availablePages?.recommended, add])

    return (
        <PageLayout>
            <div className="flex-grow flex flex-col lg:flex-row w-full overflow-hidden">
                <div className="bg-gray-100 flex-grow pt-8 pb-6 px-12 md:pt-16 md:pb-8 md:px-16 xl:px-28">
                    <div className="h-full flex flex-col">
                        <div
                            ref={pagePreviewRef}
                            className="h-80 lg:h-auto flex-grow overflow-y-scroll rounded-lg shadow-lg relative">
                            {previewing && !loading && (
                                <PagePreview
                                    ref={pagePreviewRef}
                                    style={styleMemo}
                                />
                            )}
                        </div>
                        <h3 className="text-base lg:text-lg font-medium text-gray-700 text-center mt-4 lg:mt-6 mb-0">
                            {previewing?.name}
                        </h3>
                    </div>
                </div>
                <div className="w-full lg:max-w-lg flex-col px-12 py-16 overflow-y-scroll">
                    <Title
                        title={__(
                            'Pick the pages to add to your website',
                            'extendify',
                        )}
                        description={__(
                            'We already selected the most common pages for your type of website.',
                            'extendify',
                        )}
                    />
                    <div className="flex flex-col gap-4 pb-4">
                        <PageSelectButton
                            page={homePage}
                            previewing={homePage.id === previewing?.id}
                            onPreview={() => setPreviewing(homePage)}
                            checked={true}
                            forceChecked={true}
                            onChange={() => undefined}
                        />
                        {availablePages?.recommended?.map((page) => (
                            <PageSelectButton
                                key={page.id}
                                page={page}
                                previewing={page.id === previewing?.id}
                                onPreview={() => setPreviewing(page)}
                                checked={has('pages', page)}
                                onChange={() => handlePageToggle(page)}
                            />
                        ))}
                    </div>
                    <div className="flex items-center justify-center">
                        <button
                            type="button"
                            onClick={setExpandMore}
                            className="bg-transparent text-sm text-center font-medium text-gray-900 my-4 cursor-pointer hover:text-design-main button-focus">
                            {__('View more pages', 'extendify')}
                        </button>
                    </div>
                    {expandMore && (
                        <div className="flex flex-col gap-4 pb-4">
                            {availablePages?.optional?.map((page) => (
                                <PageSelectButton
                                    key={page.id}
                                    page={page}
                                    previewing={page.id === previewing?.id}
                                    onPreview={() => setPreviewing(page)}
                                    checked={pages?.some(
                                        (p) => p.id === page.id,
                                    )}
                                    onChange={() => handlePageToggle(page)}
                                />
                            ))}
                        </div>
                    )}
                </div>
            </div>
        </PageLayout>
    )
}
