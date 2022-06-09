import { registerBlockType } from '@wordpress/blocks'
import { useDispatch } from '@wordpress/data'
import { useEffect } from '@wordpress/element'
import { __, _x, sprintf } from '@wordpress/i18n'
import {
    Icon,
    gallery,
    postAuthor,
    mapMarker,
    button,
    cover,
    overlayText,
} from '@wordpress/icons'
import { brandBlockIcon } from '@library/components/icons'
import { setModalVisibility } from '@library/util/general'
import metadata from './block.json'

export const openModal = (source) => setModalVisibility(source, 'open')

registerBlockType(metadata, {
    icon: brandBlockIcon,
    category: 'extendify',
    example: {
        attributes: {
            preview: window.extendifyData.asset_path + '/preview.png',
        },
    },
    variations: [
        {
            name: 'gallery',
            icon: <Icon icon={gallery} />,
            category: 'extendify',
            attributes: { search: 'gallery' },
            title: __('Gallery Patterns', 'extendify'),
            description: __('Add gallery patterns and layouts.', 'extendify'),
            keywords: [__('slideshow', 'extendify'), __('images', 'extendify')],
        },
        {
            name: 'team',
            icon: <Icon icon={postAuthor} />,
            category: 'extendify',
            attributes: { search: 'team' },
            title: __('Team Patterns', 'extendify'),
            description: __('Add team patterns and layouts.', 'extendify'),
            keywords: [
                _x('crew', 'As in team', 'extendify'),
                __('colleagues', 'extendify'),
                __('members', 'extendify'),
            ],
        },
        {
            name: 'hero',
            icon: <Icon icon={cover} />,
            category: 'extendify',
            attributes: { search: 'hero' },
            title: _x(
                'Hero Patterns',
                'Hero being a hero/top section of a webpage',
                'extendify',
            ),
            description: __('Add hero patterns and layouts.', 'extendify'),
            keywords: [__('heading', 'extendify'), __('headline', 'extendify')],
        },
        {
            name: 'text',
            icon: <Icon icon={overlayText} />,
            category: 'extendify',
            attributes: { search: 'text' },
            title: _x(
                'Text Patterns',
                'Relating to patterns that feature text only',
                'extendify',
            ),
            description: __('Add text patterns and layouts.', 'extendify'),
            keywords: [__('simple', 'extendify'), __('paragraph', 'extendify')],
        },
        {
            name: 'about',
            icon: <Icon icon={mapMarker} />,
            category: 'extendify',
            attributes: { search: 'about' },
            title: _x(
                'About Page Patterns',
                'Add patterns relating to an about us page',
                'extendify',
            ),
            description: __('About patterns and layouts.', 'extendify'),
            keywords: [__('who we are', 'extendify'), __('team', 'extendify')],
        },
        {
            name: 'call-to-action',
            icon: <Icon icon={button} />,
            category: 'extendify',
            attributes: { search: 'call-to-action' },
            title: __('Call to Action Patterns', 'extendify'),
            description: __(
                'Add call to action patterns and layouts.',
                'extendify',
            ),
            keywords: [
                _x('cta', 'Initialism for call to action', 'extendify'),
                __('callout', 'extendify'),
                __('buttons', 'extendify'),
            ],
        },
    ],
    edit: function Edit({ clientId, attributes }) {
        const { removeBlock } = useDispatch('core/block-editor')
        useEffect(() => {
            if (attributes.preview) {
                return
            }
            if (attributes.search) {
                addTermToSearchParams(attributes.search)
            }
            openModal('library-block')
            removeBlock(clientId)
        }, [clientId, attributes, removeBlock])
        return (
            <img
                style={{ display: 'block', maxWidth: '100%' }}
                src={attributes.preview}
                alt={sprintf(
                    __('%s Pattern Library', 'extendify'),
                    'Extendify',
                )}
            />
        )
    },
})

const addTermToSearchParams = (term) => {
    const params = new URLSearchParams(window.location.search)
    params.append('ext-patternType', term)
    window.history.replaceState(
        null,
        null,
        window.location.pathname + '?' + params.toString(),
    )
}
