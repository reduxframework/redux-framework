import { Spinner } from '@wordpress/components'
import {
    useState, useEffect, useRef,
} from '@wordpress/element'
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
                // Check the image wasn't already appended
                if (imageContainerRef.current && !imageContainerRef.current.querySelector('img')) {
                    imageContainerRef.current.appendChild(img)
                    imageLoaded(template.id)
                }
            }
        }
        img.src = template?.fields?.screenshot[0]?.thumbnails?.large?.url ?? template?.fields?.screenshot[0]?.url
    }, [template, imageLoaded, loaded, isMounted])

    if (!loaded) {
        return <TemplateButtonSkeleton/>
    }
    return (
        <button
            type="button"
            className="flex mb-10 justify-items-center flex-grow h-80 border-gray-200 bg-white border focus:border-wp-theme-500 group-hover:border-wp-theme-500 transition duration-150 cursor-pointer overflow-hidden"
            onClick={setActiveTemplate}
            ref={imageContainerRef}
        >
        </button>
    )
}
