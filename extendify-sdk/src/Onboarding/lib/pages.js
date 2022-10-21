import {
    Goals,
    fetcher as goalsFetcher,
    fetchData as goalsData,
    state as goalsState,
} from '@onboarding/pages/Goals'
import {
    SiteInformation,
    fetcher as siteInfoFetcher,
    fetchData as siteInfoData,
    state as siteInfoState,
} from '@onboarding/pages/SiteInformation'
import {
    SitePages,
    fetcher as sitePagesFetcher,
    fetchData as sitePagesData,
    state as sitePagesState,
} from '@onboarding/pages/SitePages'
import { SiteStyle, state as siteStyleState } from '@onboarding/pages/SiteStyle'
import {
    SiteSummary,
    state as confirmationState,
} from '@onboarding/pages/SiteSummary'
import {
    SiteTypeSelect,
    fetcher as siteTypeFetcher,
    fetchData as siteTypeData,
    state as siteTypeState,
} from '@onboarding/pages/SiteTypeSelect'

// pages added here will need to match the orders table on the Styles base
const defaultPages = [
    [
        'site-type',
        {
            component: SiteTypeSelect,
            fetcher: siteTypeFetcher,
            fetchData: siteTypeData,
            state: siteTypeState,
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
        'style',
        {
            component: SiteStyle,
            state: siteStyleState,
        },
    ],
    [
        'pages',
        {
            component: SitePages,
            fetcher: sitePagesFetcher,
            fetchData: sitePagesData,
            state: sitePagesState,
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
    ['confirmation', { component: SiteSummary, state: confirmationState }],
]

const pages = defaultPages?.filter(
    (pageKey) => !window.extOnbData?.partnerSkipSteps?.includes(pageKey[0]),
)
export { pages }
