import { isBlobURL } from '@wordpress/blob'
import {
    DropZone,
    Button,
    Spinner,
    ResponsiveWrapper,
} from '@wordpress/components'
import { store as coreStore } from '@wordpress/core-data'
import { useSelect } from '@wordpress/data'
import { useEffect } from '@wordpress/element'
import { useState } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { MediaUpload, uploadMedia } from '@wordpress/media-utils'
import { getOption, updateOption } from '@assist/api/WPApi'
import { getMediaDetails } from '../lib/media'

export const ImageUploader = ({ type, onUpdate, title, actionLabel }) => {
    const [isLoading, setIsLoading] = useState(false)
    const [imageId, setImageId] = useState(0)
    const media = useSelect(
        (select) => select(coreStore).getMedia(imageId),
        [imageId],
    )
    const { mediaWidth, mediaHeight, mediaSourceUrl } = getMediaDetails(media)

    useEffect(() => {
        getOption(type).then((id) => setImageId(Number(id)))
    }, [type])

    const onUpdateImage = (image) => {
        setImageId(image.id)
        updateOption(type, image.id)
        onUpdate()
    }
    const onRemoveImage = () => {
        setImageId(0)
        updateOption(type, 0)
    }

    const onDropFiles = (filesList) => {
        uploadMedia({
            allowedTypes: ['image'],
            filesList,
            onFileChange([image]) {
                if (isBlobURL(image?.url)) {
                    setIsLoading(true)
                    return
                }
                onUpdateImage(image)
                setIsLoading(false)
            },
            onError(message) {
                console.error({ message })
            },
        })
    }

    return (
        <div>
            <MediaUploadCheck>
                <MediaUpload
                    title={title}
                    onSelect={onUpdateImage}
                    allowedTypes={['image']}
                    value={imageId}
                    modalClass=""
                    render={({ open }) => (
                        <div className="relative block">
                            <Button
                                className={
                                    'editor-post-featured-image__toggle extendify-assist-upload-logo p-0 m-0 border-0 cursor-pointer block w-full min-w-full text-center relative bg-gray-100 hover:bg-gray-300 hover:text-current h-48 items-center text-gray-900'
                                }
                                onClick={open}
                                aria-label={
                                    !imageId
                                        ? null
                                        : __(
                                              'Edit or update the image',
                                              'extendify',
                                          )
                                }
                                aria-describedby={
                                    !imageId
                                        ? null
                                        : `image-${imageId}-describedby`
                                }>
                                {Boolean(imageId) && media && (
                                    <>
                                        <ResponsiveWrapper
                                            naturalWidth={mediaWidth}
                                            naturalHeight={mediaHeight}
                                            isInline>
                                            <img
                                                className="block m-auto w-auto max-w-auto h-auto max-h-full absolute inset-0"
                                                src={mediaSourceUrl}
                                                alt=""
                                            />
                                        </ResponsiveWrapper>
                                    </>
                                )}
                                {isLoading && <Spinner />}
                                {!imageId && !isLoading && actionLabel}
                            </Button>
                            <DropZone
                                className="w-full h-full absolute inset-0"
                                onFilesDrop={onDropFiles}
                            />
                        </div>
                    )}
                />
            </MediaUploadCheck>
            {Boolean(imageId) && (
                <div className="block mt-2">
                    <MediaUploadCheck>
                        {imageId && (
                            <MediaUpload
                                title={title}
                                onSelect={onUpdateImage}
                                unstableFeaturedImageFlow
                                allowedTypes={['image']}
                                modalClass="image__media-modal"
                                render={({ open }) => (
                                    <Button onClick={open} variant="secondary">
                                        {__('Replace image', 'extendify')}
                                    </Button>
                                )}
                            />
                        )}
                        <Button
                            onClick={onRemoveImage}
                            variant="link"
                            className="ml-4"
                            isDestructive>
                            {__('Remove image', 'extendify')}
                        </Button>
                    </MediaUploadCheck>
                </div>
            )}
        </div>
    )
}

const MediaUploadCheck = ({ fallback = null, children }) => {
    const { checkingPermissions, hasUploadPermissions } = useSelect(
        (select) => {
            const core = select('core')
            return {
                hasUploadPermissions: core.canUser('read', 'media'),
                checkingPermissions: !core.hasFinishedResolution('canUser', [
                    'read',
                    'media',
                ]),
            }
        },
    )

    return (
        <>
            {checkingPermissions && <Spinner />}
            {!checkingPermissions && hasUploadPermissions ? children : fallback}
        </>
    )
}
