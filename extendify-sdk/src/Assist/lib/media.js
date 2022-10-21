import { applyFilters } from '@wordpress/hooks'

export const getMediaDetails = (media) => {
    if (!media) return {}

    const defaultSize = applyFilters(
        'editor.PostFeaturedImage.imageSize',
        'large',
        media.id,
    )
    if (defaultSize in (media?.media_details?.sizes ?? {})) {
        return {
            mediaWidth: media.media_details.sizes[defaultSize].width,
            mediaHeight: media.media_details.sizes[defaultSize].height,
            mediaSourceUrl: media.media_details.sizes[defaultSize].source_url,
        }
    }

    // Use fallbackSize when defaultSize is not available.
    const fallbackSize = applyFilters(
        'editor.PostFeaturedImage.imageSize',
        'thumbnail',
        media.id,
    )
    if (fallbackSize in (media?.media_details?.sizes ?? {})) {
        return {
            mediaWidth: media.media_details.sizes[fallbackSize].width,
            mediaHeight: media.media_details.sizes[fallbackSize].height,
            mediaSourceUrl: media.media_details.sizes[fallbackSize].source_url,
        }
    }

    // Use full image size when fallbackSize and defaultSize are not available.
    return {
        mediaWidth: media.media_details.width,
        mediaHeight: media.media_details.height,
        mediaSourceUrl: media.source_url,
    }
}
