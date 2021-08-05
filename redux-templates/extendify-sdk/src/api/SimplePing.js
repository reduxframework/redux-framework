import { Axios as api } from './axios'

export const SimplePing = {
    action(action) {
        return api.post('simple-ping', {
            action,
        })
    },
}
