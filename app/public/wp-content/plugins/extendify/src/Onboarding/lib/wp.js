import {
    updateOption,
    createPage,
    updateThemeVariation,
} from '@onboarding/api/WPApi'

export const createWordpressPages = async (pages) => {
    const pageIds = {}
    for (const page of pages) {
        pageIds[page.slug] = await createPage({
            title: page.name,
            status: 'publish',
            content: page.patterns?.map(({ code }) => code)?.join(''),
            template: 'no-title',
            meta: { made_with_extendify_launch: true },
        })
    }

    // When we have home, set reading setting
    if (pageIds?.home) {
        await updateOption('show_on_front', 'page')
        await updateOption('page_on_front', pageIds.home.id)
    }
    // When we have blog, set reading setting
    if (pageIds?.blog) {
        await updateOption('page_for_posts', pageIds.blog.id)
    }

    return pageIds
}

export const updateGlobalStyleVariant = (variation) =>
    updateThemeVariation(window.extOnbData.globalStylesPostID, variation)
