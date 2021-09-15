import { Spinner, Button } from '@wordpress/components'
import {
    useState, useEffect, useRef,
} from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { useIsMounted } from '../hooks/helpers'

export const TemplateButtonSkeleton = () => <div className="aspect-w-1 aspect-h-1">
    <div className="w-full h-full flex items-center justify-center bg-gray-100">
        <Spinner/>
    </div>
</div>

export default function TemplateButton({ template, imageLoaded, setActiveTemplate }) {
    const [loaded, setLoaded] = useState(false)
    const imageContainerRef = useRef()
    const isMounted = useIsMounted()

    useEffect(() => {
        if (loaded || !isMounted.current) {
            return
        }
        const img = new Image()
        img.role = 'button'
        img.className = 'max-w-full block m-auto object-cover'
        img.onload = () => {
            if (isMounted.current) {
                setLoaded(true)
                imageContainerRef.current && imageContainerRef.current.appendChild(img)
                imageLoaded(template.id)
            }
        }
        img.src = template?.fields?.screenshot[0]?.thumbnails?.large?.url ?? template?.fields?.screenshot[0]?.url
    }, [template, imageLoaded, loaded, isMounted])

    if (!loaded) {
        return <TemplateButtonSkeleton/>
    }
    return <div className="flex flex-col justify-between group overflow-hidden max-w-lg">
        {/* Note: This doesn't have tabindex nor keyboard event on purpose. a11y tabs to the button only */}
        <div
            className="flex justify-items-center flex-grow h-80 border-gray-200 bg-white border border-b-0 group-hover:border-wp-theme-500 transition duration-150 cursor-pointer overflow-hidden"
            onClick={setActiveTemplate}
            ref={imageContainerRef}>
        </div>
        <span
            role="img"
            aria-hidden="true"
            className="h-px w-full bg-gray-200 border group-hover:bg-transparent border-t-0 border-b-0 border-gray-200 group-hover:border-wp-theme-500 transition duration-150"></span>
        <div
            className="bg-transparent text-left bg-white flex items-center justify-between p-4 border border-t-0 border-transparent group-hover:border-wp-theme-500 transition duration-150 cursor-pointer"
            role="button"
            onClick={setActiveTemplate}>
            <div>
                <h4 className="m-0 font-bold">{template.fields.display_title}</h4>
                <p className="m-0">{template?.fields?.tax_categories?.filter(c => c.toLowerCase() !== 'default').join(', ')}</p>
            </div>
            <Button
                isSecondary
                className="sm:opacity-0 group-hover:opacity-100 transition duration-150 focus:opacity-100"
                onClick={(e) => {e.stopPropagation();setActiveTemplate()}}>
                {__('View', 'extendify-sdk')}
            </Button>
        </div>
    </div>
}
