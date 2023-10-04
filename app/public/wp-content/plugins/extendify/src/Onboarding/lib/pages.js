import {
    Goals,
    goalsFetcher,
    goalsParams as goalsData,
    state as goalsState,
} from '@onboarding/pages/Goals'
import {
    HomeSelect,
    fetcher as homeSelectFetcher,
    fetchData as homeSelectData,
    state as homeSelectState,
} from '@onboarding/pages/HomeSelect'
import {
    PagesSelect,
    fetcher as pagesSelectFetcher,
    fetchData as pagesSelectData,
    state as pagesSelectState,
} from '@onboarding/pages/PagesSelect'
import {
    SiteInformation,
    fetcher as siteInfoFetcher,
    fetchData as siteInfoData,
    state as siteInfoState,
} from '@onboarding/pages/SiteInformation'
import {
    SiteTypeSelect,
    state as siteTypeState,
} from '@onboarding/pages/SiteTypeSelect'

// pages added here will need to match the orders table on the Styles base
const defaultPages = [
    [
        'site-type',
        {
            component: SiteTypeSelect,
            state: siteTypeState,
        },
    ],
    [
        'site-title',
        {
            component: SiteInformation,
            fetcher: siteInfoFetcher,
            fetchData: siteInfoData,
            state: siteInfoState,
        },
    ],
    [
        'goals',
        {
            component: Goals,
            fetcher: goalsFetcher,
            fetchData: goalsData,
            state: goalsState,
        },
    ],
    [
        'layout',
        {
            component: HomeSelect,
            fetcher: homeSelectFetcher,
            fetchData: homeSelectData,
            state: homeSelectState,
        },
    ],
    [
        'pages',
        {
            component: PagesSelect,
            fetcher: pagesSelectFetcher,
            fetchData: pagesSelectData,
            state: pagesSelectState,
        },
    ],
]

const pages = defaultPages?.filter(
    (pageKey) => !window.extOnbData?.partnerSkipSteps?.includes(pageKey[0]),
)
export { pages }
