import useSWR from 'swr'

export const useTaskDependencies = (params, fetcher, refresh) =>
    useFetch(params, fetcher, {
        refreshInterval: refresh ? 5000 : 0,
        dedupingInterval: refresh ? 5000 : 60_000,
    })

const useFetch = (params, fetcher, options = {}) => {
    const { data: fetchedData, error } = useSWR(
        params,
        async (key) => {
            const response = await fetcher(key)
            if (response?.data === undefined || !response) {
                console.error(response)
                // This is here in response to CloudFlare intercepting
                // and redirecting responses
                throw new Error('No data returned')
            }
            return response
        },
        {
            dedupingInterval: 60_000,
            refreshInterval: 0,
            ...options,
        },
    )
    const data = fetchedData?.data
    return { data, loading: data === undefined && !error, error }
}
