import {
    useEffect, useState, render, unmountComponentAtNode,
} from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { useGlobalStore } from '../../state/GlobalState'
import { useUserStore } from '../../state/User'
import { General as GeneralApi } from '../../api/General'
import { useTaxonomyStore } from '../../state/Taxonomies'
import classNames from 'classnames'
import { useTemplatesStore } from '../../state/Templates'
import SearchPredict from '../../components/SearchPredict'
import { Transition } from '@headlessui/react'
import WaitingCrunching from '../modals/WaitingCrunchingModal'
import { Templates as TemplatesApi } from '../../api/Templates'

export default function GuideSteps() {
    const updateSearchParams = useTemplatesStore(state => state.updateSearchParams)
    const templates = useTemplatesStore(state => state.templates)
    const taxonomyDefaultState = useTemplatesStore(state => state.taxonomyDefaultState)
    const setActiveTemplate = useTemplatesStore(state => state.setActive)
    const appendTemplates = useTemplatesStore(state => state.appendTemplates)
    const updateTaxonomies = useTemplatesStore(state => state.updateTaxonomies)
    const taxonomies = useTaxonomyStore(state => state.taxonomies)
    const preferred = useUserStore(state => state.preferredOptions)
    const [allCats, setAllCats] = useState([])
    const [stepOneTouched, setStepOneTouched] = useState(false)
    const [stepTwoTouched, setStepTwoTouched] = useState(false)
    const [stepThreeTouched, setStepThreeTouched] = useState(false)

    const setPreferred = (key, value) => {
        useUserStore.getState().updatePreferredOption(key, value)
    }
    const typeTax = preferred?.type == 'template'
        ? 'tax_page_types'
        : 'tax_pattern_types'

    const closeGuide = () => {
        GeneralApi.ping('guide-cancelled')
        templates.length && useTemplatesStore.setState({ skipNextFetch: true })
        useGlobalStore.setState({ currentPage: 'main' })
    }
    const fetchDelayThenDisplay = () => {
        updateSearchParams({
            taxonomies: Object.assign(
                {}, taxonomyDefaultState, preferred.taxonomies,
            ),
            type: preferred.type,
            search: preferred.search,
        })
        const action = new Promise((resolve) => {
            useTemplatesStore.setState({ skipNextFetch: true })
            const setupTemplates = (data) => {
                appendTemplates(data)
                data.length === 1 && setActiveTemplate(data[0])
                useTemplatesStore.setState({
                    nextPage: data.offset,
                })
            }
            // TODO: this could probably be smarter and recursive we want to remove more
            TemplatesApi.get(useTemplatesStore.getState().searchParams).then((response) => {
                if (response.records.length) {
                    setupTemplates(response.records)
                    return resolve()
                }
                // Remove the style and try again
                updateTaxonomies({ tax_style: '' })
                TemplatesApi.get(useTemplatesStore.getState().searchParams).then((response) => {
                    setupTemplates(response.records)
                    return resolve()
                })
            })
        })
        const callback = async () => {
            await new Promise((resolve) => setTimeout(resolve, 1500))
            useGlobalStore.setState({
                currentPage: 'main',
            })
            unmountComponentAtNode(document.getElementById('extendify-util'))
        }
        render(<WaitingCrunching
            action={action}
            callback={callback}
            text={__('Finding templates...', 'extendify-sdk')}
        />, document.getElementById('extendify-util'))
    }

    const showStepTwo = () => (stepOneTouched || preferred?.taxonomies?.tax_categories) ? true : false
    const showStepThree = () => (showStepTwo() && (!taxonomies[typeTax] || stepTwoTouched || preferred?.taxonomies[typeTax])) ? true : false
    const showFinalButton = () => (showStepThree() && (stepThreeTouched || preferred?.taxonomies?.tax_style)) ? true : false

    useEffect(() => {
        if (!taxonomies?.tax_categories) {
            return
        }

        const all = Object.values(taxonomies.tax_categories)
            // Map over all terms
            .map((term) =>
                // Filter out terms not of this type (pattern/template)
                term.children.filter(c => c.type.includes(preferred?.type)).map(c => c.term))
            // merge all together
            .flat().sort()

        setAllCats([...new Set(all)])
    }, [taxonomies, preferred?.type])

    const emptyToolbar = <div className="w-full h-16 relative z-10 border-solid border-0 flex-shrink-0">
        <div className="flex justify-between items-center px-6 sm:px-12 h-full">
            <div className="flex space-x-12 h-full">
            </div>
            <div className="space-x-2 transform sm:translate-x-8">
                <button
                    type="button"
                    className="components-button has-icon"
                    onClick={closeGuide}>
                    <svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" size="24" role="img" aria-hidden="true" focusable="false"><path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path></svg>
                    <span className="sr-only">{__('Close library', 'extendify-sdk')}</span>
                </button>
            </div>
        </div>
    </div>

    return <div className="w-full h-full flex flex-col items-center relative shadow-xl max-w-screen-4xl mx-auto bg-white">
        {emptyToolbar}
        <section className="flex-grow w-full justify-between flex flex-col overflow-y-scroll">
            <div className="flex flex-col w-full h-full p-8 pb-0 md:p-16 md:pb-0 2xl:p-28 2xl:pb-0 max-w-screen-2xl mx-auto">
                <h1 className="text-left m-0 mb-12 text-7xl">
                    {__('Hello', 'extendify-sdk')}
                </h1>
                <div className="flex-grow lg:flex justify-between space-y-8 lg:space-y-0 lg:space-x-16 xl:space-x-32">
                    <div className="text-left flex-shrink-0 lg:w-1/2">
                        <h2 className="text-2xl m-0 mb-8">
                            {preferred?.type === 'template' ?
                                __('Help me find a template', 'extendify-sdk') :
                                __('Help me find a pattern', 'extendify-sdk')}
                        </h2>
                        <div className="flex flex-col space-y-8">
                            <div>
                                <p className="text-base text-gray-900 m-0 mb-5">{__('Select your industry:', 'extendify-sdk')}</p>
                                <SearchPredict
                                    list={allCats}
                                    value={preferred?.taxonomies?.tax_categories}
                                    touched={() => setStepOneTouched(true)}
                                    label={__('Category', 'extendify-sdk')}
                                    setValue={(v) => setPreferred('tax_categories', v)}/>
                            </div>

                            <Transition
                                enter="transform transition duration-50 duration-300"
                                enterFrom="opacity-0 translate-y-2"
                                enterTo="opacity-100 translate-y-0"
                                show={showStepTwo()}>
                                {taxonomies[typeTax] && <div onChange={() => setStepTwoTouched(true)}>
                                    <label
                                        className="text-base text-gray-900 m-0 mb-4 block"
                                        htmlFor="typeTax-search">
                                        {__('What type of section are you trying to add?', 'extendify-sdk')}
                                    </label>
                                    <select
                                        onChange={(event) => setPreferred(typeTax, event.target.value)}
                                        value={preferred?.taxonomies[typeTax] ?? ''}
                                        id="typeTax-search"
                                        className="h-8 max-w-md min-h-0 w-full px-2 text-sm border border-gray-900 button-focus-big-green rounded-none">
                                        <option value="">{__('Select type', 'extendify-sdk')}</option>
                                        {Object.values(taxonomies[typeTax]).map((t) => {
                                            return <option key={t.term} value={t.term}>
                                                {t.term}
                                            </option>
                                        })}
                                    </select>
                                </div>}
                            </Transition>
                        </div>
                    </div>
                    <div className="mt-16 text-left">
                        <Transition
                            enter="transform transition duration-50 duration-300"
                            enterFrom="opacity-0 translate-y-2"
                            enterTo="opacity-100 translate-y-0"
                            show={showStepThree()}>
                            <div onClick={() => setStepThreeTouched(true)}>
                                <p className="text-base text-gray-900 m-0 mb-4">
                                    {__('What style best matches what you\'re looking for?', 'extendify-sdk')}
                                </p>
                                <div>
                                    {taxonomies?.tax_style && <div className="grid grid-cols-2 gap-4 mb-8">
                                        {Object.values(taxonomies.tax_style)
                                            .filter((t) => t?.type?.includes(preferred?.type) && t?.thumbnail)
                                            .map((t) => {
                                                return <button
                                                    key={t.term}
                                                    onClick={() => setPreferred('tax_style', t.term)}
                                                    className={classNames({
                                                        'bg-transparent p-0 m-0 cursor-pointer': true,
                                                        'ring-4 ring-offset-4 ring-extendify-main outline-none': t.term === preferred?.taxonomies?.tax_style,
                                                    })}>
                                                    <span className="sr-only">{t.term}</span>
                                                    <img className="w-full" src={t.thumbnail} alt={`Style named ${t.term}`} />
                                                </button>
                                            })}
                                    </div>}
                                </div>
                            </div>
                        </Transition>
                        <Transition
                            enter="transform transition duration-50 duration-300"
                            enterFrom="opacity-0 translate-y-2"
                            enterTo="opacity-100 translate-y-0"
                            show={showFinalButton()}>
                            <button
                                onClick={() => fetchDelayThenDisplay()}
                                className="button-extendify-main button-focus-big-green p-4 text-xl">
                                {preferred?.type === 'template' ?
                                    __('View templates', 'extendify-sdk') :
                                    __('View patterns', 'extendify-sdk')}
                            </button>
                        </Transition>
                    </div>
                </div>
            </div>
            <footer className="flex justify-between p-14 w-full">
                <div>
                    <svg className="block" width="64" height="64" viewBox="0 0 103 103" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect y="25.75" width="70.8125" height="77.25" fill="#000000"/>
                        <rect x="45.0625" width="57.9375" height="57.9375" fill="#37C2A2"/>
                    </svg>
                </div>
            </footer>
        </section>
    </div>
}
