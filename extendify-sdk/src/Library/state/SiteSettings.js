import create from 'zustand'
import { persist } from 'zustand/middleware'
import { SiteSettings } from '@library/api/SiteSettings'

const storage = {
    getItem: async () => await SiteSettings.getData(),
    setItem: async (_name, value) => await SiteSettings.setData(value),
    removeItem: () => {},
}

export const useSiteSettingsStore = create(
    persist(
        (set) => ({
            enabled: true,
            siteType: {},
            setSiteType: async (siteType) => {
                set({ siteType })
                await SiteSettings.updateOption('extendify_siteType', siteType)
            },
        }),
        {
            name: 'extendify-sitesettings',
            getStorage: () => storage,
        },
    ),
)
