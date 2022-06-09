import {
    Goals,
    fetcher as goalsFetcher,
    fetchData as goalsData,
} from '@onboarding/pages/Goals'
import { Landing } from '@onboarding/pages/Landing'
import {
    SiteInformation,
    fetcher as siteInfoFetcher,
    fetchData as siteInfoData,
} from '@onboarding/pages/SiteInformation'
import {
    SitePages,
    fetcher as availablePagesFetcher,
    fetchData as availablePagesData,
} from '@onboarding/pages/SitePages'
import { SiteStyle } from '@onboarding/pages/SiteStyle'
import { SiteSummary } from '@onboarding/pages/SiteSummary'
import {
    SiteTypeSelect,
    fetcher as siteTypeFetcher,
    fetchData as siteTypeData,
} from '@onboarding/pages/SiteTypeSelect'

// import {
//     SuggestedPlugins,
//     fetcher as suggestedPluginsFetcher,
//     fetchData as suggestedPluginsData,
// } from '@onboarding/pages/SuggestedPlugins'

export const pages = [
    ['landing', { component: Landing }],
    [
        'site-information',
        {
            component: SiteInformation,
            fetcher: siteInfoFetcher,
            fetchData: siteInfoData,
        },
    ],
    // [
    //     'suggested-plugins',
    //     {
    //         component: SuggestedPlugins,
    //         fetcher: suggestedPluginsFetcher,
    //         fetchData: suggestedPluginsData,
    //     },
    // ],
    [
        'site-type-select',
        {
            component: SiteTypeSelect,
            fetcher: siteTypeFetcher,
            fetchData: siteTypeData,
        },
    ],
    [
        'goals',
        {
            component: Goals,
            fetcher: goalsFetcher,
            fetchData: goalsData,
        },
    ],
    ['site-style-select', { component: SiteStyle }],
    [
        'site-pages-select',
        {
            component: SitePages,
            fetcher: availablePagesFetcher,
            fetchData: availablePagesData,
        },
    ],
    ['site-summary', { component: SiteSummary }],
]
