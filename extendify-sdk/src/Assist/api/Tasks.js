import { Axios as api } from './axios'

export const getTasks = () => api.get('assist/tasks')
export const getTaskData = () => api.get('assist/task-data')
export const saveTaskData = (data) => api.post('assist/task-data', { data })
