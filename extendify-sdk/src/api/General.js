import { useUserStore } from '../state/User'
import { Axios as api } from './axios'

export const General = {
    metaData() {
        return api.get('meta-data')
    },
    ping(action) {
        return api.post('simple-ping', {
            action,
            sdk_partner: useUserStore.getState()?.sdkPartner ?? '',
        })
    },
}
