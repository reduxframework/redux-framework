import { __, sprintf } from '@wordpress/i18n'
import { registerBlockType } from '@wordpress/blocks'
import { useDispatch } from '@wordpress/data'
import { useEffect } from '@wordpress/element'
import { brandBlockIcon } from '../../components/icons'
import { setModalVisibility } from '../../util/general'
import metadata from './block.json'

export const openModal = (source) => setModalVisibility(source, 'open')

registerBlockType(metadata, {
    icon: brandBlockIcon,
    example: {
        attributes: {
            preview: window.extendifyData.asset_path + '/preview.png',
        },
    },
    edit: function Edit({ clientId, attributes }) {
        const { removeBlock } = useDispatch('core/block-editor')
        useEffect(() => {
            if (attributes.preview) {
                return
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
