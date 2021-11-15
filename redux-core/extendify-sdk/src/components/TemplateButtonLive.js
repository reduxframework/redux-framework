import { Spinner } from '@wordpress/components'
import { BlockPreview } from '@wordpress/block-editor'
import { rawHandler } from '@wordpress/blocks'
import { __, sprintf } from '@wordpress/i18n'
import {
    useState, useEffect, useRef, memo,
} from '@wordpress/element'
import { useInView } from 'react-intersection-observer'

export const TemplateButtonLiveSkeleton = ({ extraClasses }) => (
    <div className={`w-full h-40 flex items-center justify-center bg-gray-100 ${extraClasses}`}>
        <Spinner/>
    </div>
)

const LiveBlocksMemoized = memo(({ blocks }) => {
    return <BlockPreview
        blocks={blocks}
        live={false}
        viewportWidth={1400}/>
}, (oldBlocks, newBlocks) => oldBlocks.clientId == newBlocks.clientId)

export default function TemplateButtonLive({ template, setActiveTemplate }) {
    // Converts HTML to blocks
    const blocks = rawHandler({ HTML: template.fields.code })
    const [hasBeenSeen, setHasBeenSeen] = useState(false)
    const previewRef = useRef()
    const [onlyLoadInView, inView] = useInView()

    // This makes sure the component doesn't stop rendering before it's
    // memoized in case the user scrolls too fast. ie once it's in view once, it
    // will always be considered inView and visible
    useEffect(() => {
        if (!hasBeenSeen && inView) {
            setHasBeenSeen(true)
        }
    }, [inView, hasBeenSeen])

    // The live preview injects real, rendered html inside the component (not in an iFrame),
    // which includes any sort of html elements, so we have can't use a button here.
    return <div
        role="button"
        aria-label={sprintf(__('Open details about the %s', 'extendify-sdk'), template?.fields?.type)}
        tabIndex="0"
        className="mb-10 w-full relative focus:border-wp-theme-500 group-hover:border-wp-theme-500 transition duration-150 cursor-pointer overflow-hidden"
        onClick={setActiveTemplate}
        onKeyDown={(e) => ['Enter', 'Space'].includes(e.key) && setActiveTemplate() }
        ref={previewRef}
    >
        <div ref={onlyLoadInView} className="invisible absolute inset-0 pointer-events-none"></div>
        {hasBeenSeen && <LiveBlocksMemoized blocks={blocks} />}
    </div>
}
