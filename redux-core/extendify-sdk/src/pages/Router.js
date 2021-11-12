import Welcome from './guide/Welcome.js'
// import GuideStart from './guide/GuideStart.js'
// import GuideSteps from './guide/GuideSteps.js'
import GridView from './GridView.js'
import CuratedView from './CuratedView.js'
import SingleView from './SingleView.js'
import Login from './Login.js'
import WaitingCrunching from './modals/WaitingCrunchingModal.js'
import { useTemplatesStore } from '../state/Templates.js'

// Probably the most basic router you can imagine
export default function Router({ page }) {
    const searchParams = useTemplatesStore(state => state.searchParams)

    // TODO: Possibly we can have a loading screen while we fetch terms, etc
    if (page === 'main' && !Object.keys(searchParams?.taxonomies ?? {}).length) {
        page = 'curated'
    }

    // Reroute the main page depending on the taxonomy and type choices
    // If no pattern types are selected, show a curated page
    if (page === 'main' &&
        searchParams?.type === 'pattern' &&
        searchParams?.taxonomies?.tax_pattern_types === '')
    {
        page = 'curated'
    }

    switch (page) {
        case 'welcome': return <Welcome/>
        // case 'guide-start': return <GuideStart/>
        // case 'guide-steps': return <GuideSteps/>
        case 'waiting-crunching': return <WaitingCrunching/>
        case 'curated': return <CuratedView/>
        case 'main': return <GridView/>
        case 'single': return <SingleView/>
        case 'login': return <Login/>
    }
}
