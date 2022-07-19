import {
    Goals,
    fetcher as goalsFetcher,
    fetchData as goalsData,
    metadata as goalsMeta,
} from '@onboarding/pages/Goals'
import { Landing, metadata as landingMeta } from '@onboarding/pages/Landing'
import {
    SiteInformation,
    fetcher as siteInfoFetcher,
    fetchData as siteInfoData,
} from '@onboarding/pages/SiteInformation'
import {
    SitePages,
    fetcher as sitePagesFetcher,
    fetchData as sitePagesData,
    metadata as sitePagesMeta,
} from '@onboarding/pages/SitePages'
import {
    SiteStyle,
    metadata as siteStyleMeta,
} from '@onboarding/pages/SiteStyle'
import {
    SiteSummary,
    metadata as confirmationMeta,
} from '@onboarding/pages/SiteSummary'
import {
    SiteTypeSelect,
    fetcher as siteTypeFetcher,
    fetchData as siteTypeData,
    metadata as siteTypeMeta,
} from '@onboarding/pages/SiteTypeSelect'

// pages added here will need to match the orders table on the Styles base
const defaultPages = [
    [
        'welcome',
        {
            component: Landing,
            metadata: landingMeta,
        },
    ],
    [
        'site-title',
        {
            component: SiteInformation,
            fetcher: siteInfoFetcher,
            fetchData: siteInfoData,
        },
    ],
    [
        'site-type',
        {
            component: SiteTypeSelect,
            fetcher: siteTypeFetcher,
            fetchData: siteTypeData,
            metadata: siteTypeMeta,
        },
    ],
    [
        'goals',
        {
            component: Goals,
            fetcher: goalsFetcher,
            fetchData: goalsData,
            metadata: goalsMeta,
        },
    ],
    [
        'style',
        {
            component: SiteStyle,
            metadata: siteStyleMeta,
        },
    ],
    [
        'pages',
        {
            component: SitePages,
            fetcher: sitePagesFetcher,
            fetchData: sitePagesData,
            metadata: sitePagesMeta,
        },
    ],
    [
        'confirmation',
        {
            component: SiteSummary,
            metadata: confirmationMeta,
        },
    ],
]

const pages = defaultPages.filter(
    (pageKey) => !window.extOnbData?.partnerSkipSteps?.includes(pageKey[0]),
)
export { pages }
