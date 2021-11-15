import GridView from './GridView.js'
import SingleView from './SingleView.js'

// This used to be doing more so we may be able to
// refactor it out since it's barely being used now
export default function Router({ page }) {

    switch (page) {
        case 'main': return <GridView/>
        case 'single': return <SingleView/>
    }
}
