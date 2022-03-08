import { useTemplatesStore } from '@extendify/state/Templates'
import { useUserStore } from '@extendify/state/User'
import { Axios as api } from './axios'

export const General = {
    metaData() {
        return api.get('meta-data')
    },
    ping(action) {
        const categories =
            useTemplatesStore.getState()?.searchParams?.taxonomies ?? []
        return api.post('simple-ping', {
            action,
            categories,
            sdk_partner: useUserStore.getState()?.sdkPartner ?? '',
        })
    },
}
