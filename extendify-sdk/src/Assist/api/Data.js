import { Axios as api } from './axios'

export const getTasks = () => api.get('assist/tasks')
export const getTaskData = () => api.get('assist/task-data')
export const saveTaskData = (data) => api.post('assist/task-data', { data })

export const getTourData = () => api.get('assist/tour-data')
export const saveTourData = (data) => api.post('assist/tour-data', { data })

export const getGlobalData = () => api.get('assist/global-data')
export const saveGlobalData = (data) => api.post('assist/global-data', { data })

export const getUserSelectionData = () => api.get('assist/user-selection-data')
export const saveUserSelectionData = (data) =>
    api.post('assist/user-selection-data', { data })
