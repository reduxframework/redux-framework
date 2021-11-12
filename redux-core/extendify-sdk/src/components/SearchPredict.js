import {
    useEffect, useState, useRef,
} from '@wordpress/element'
import Fuse from 'fuse.js'

export default function SearchPredict({ value, setValue, list, label, touched }) {
    const searchRef = useRef()
    const wasFocused = useRef(false)
    const [componentId] = useState('extendify-label' + Date.now() + Math.floor(Math.random() * 1000000))
    const [fuzzy, setFuzzy] = useState({})
    const [tempValue, setTempValue] = useState('')
    const [visibleChoices, setVisibleChoices] = useState([])

    const updateSearch = (term) => {
        wasFocused.current = true
        setTempValue(term)
        filter(term)
    }

    const filter = (term = '') => {
        if (!term) {
            setVisibleChoices(list)
        }
        const results = fuzzy.search(term)
        if (!results || !results.length) {
            return
        }
        setVisibleChoices(results.length ? results.map(t => t.item) : list)
    }

    useEffect(() => {
        setTempValue(value)
    }, [value])

    useEffect(() => {
        setFuzzy(new Fuse(list, {}))
    }, [list])

    useEffect(() => {
        if (!list.length) {
            return
        }
        const handle = (event) => {
            if (searchRef.current.isSameNode(event.target)) {
                return
            }
            if (event.target.classList.contains(`extendify-predict-${componentId}`)) {
                return setVisibleChoices([])
            }
            setVisibleChoices([])
            if (!list.includes(searchRef.current.value)) {
                setValue('')
            }

            // Consider the component touched when clicked away
            wasFocused.current && touched()
        }
        document.addEventListener('click', handle)
        return () => document.removeEventListener('click', handle)
    }, [componentId, touched, setValue, list])

    return <div className="relative max-w-md">
        <input
            ref={searchRef}
            id={componentId}
            value={tempValue || ''}
            onChange={(event) => updateSearch(event.target.value)}
            onFocus={() => updateSearch('')}
            type="text"
            disabled={!Object.keys(fuzzy).length}
            className="extendify-special-input button-focus text-sm h-8 min-h-0 border border-gray-900 special-input placeholder-transparent rounded-none w-full px-2 button-focus-big-green"
            placeholder={label} />
        <label htmlFor={componentId} className="-top-3 bg-white absolute left-1 px-1 transition-all delay-300">
            {label}
        </label>
        {/* TODO: this could use some accessability updates like keyboard nav, etc */}
        {visibleChoices && <div className="absolute top-100 flex flex-col w-full shadow-md bg-white overflow-x-hidden left-px divide-y max-h-64 overflow-scroll">
            {visibleChoices.map((cat =>
                <button
                    key={cat}
                    type="button"
                    className={`outline-none focus:bg-gray-100 bg-white text-left p-4 text-sm border-gray-300 hover:bg-gray-100 extendify-predict-${componentId}`}
                    onClick={() => { setValue(cat); setTempValue(cat)}}>
                    {cat}
                </button>
            ))}
        </div>}
    </div>
}
