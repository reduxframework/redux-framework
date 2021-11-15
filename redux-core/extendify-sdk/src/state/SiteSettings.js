import create from 'zustand'
import { persist } from 'zustand/middleware'
import { SiteSettings } from '../api/SiteSettings'

const storage = {
    getItem: async () => await SiteSettings.getData(),
    setItem: async (_name, value) => await SiteSettings.setData(value),
}

export const useSiteSettingsStore = create(persist(() => ({
    enabled: true,
}), {
    name: 'extendify-sitesettings',
    getStorage: () => storage,
}))
