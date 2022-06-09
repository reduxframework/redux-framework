import { useSWRConfig } from 'swr'

export const useCache = () => {
    const { cache } = useSWRConfig()
    return (keyToMatch) => cache.get(findKey(keyToMatch, cache))
}
const findKey = (partial, map) => {
    for (const key of map.keys()) {
        if (key.match(partial)) return key
    }
}
