const {__} = wp.i18n;
const {compose} = wp.compose;
const {withDispatch, withSelect, select} = wp.data;
const {useState, useEffect} = wp.element;
const {apiFetch} = wp;
const {parse} = wp.blocks;
const {Spinner} = wp.components;
import CreatableSelect from 'react-select/creatable';
import {BlockPreview} from '@wordpress/block-editor';
import {Modal, ModalManager} from '../../modal-manager'
import uniq from 'lodash/uniq';
import map from 'lodash/map';
import sortBy from 'lodash/sortBy';
import {installedBlocksTypes} from '~redux-templates/stores/actionHelper';
import {getWithExpiry} from '../../stores/helper';
import './style.scss'


const customStyles = {
    container: (provided, state) => ({
        ...provided,
        width: 300
    }),
    menu: (provided, state) => ({
        ...provided,
        marginTop: 0
    }),
    menuList: (provided, state) => ({
        ...provided,
        height: 180
    }),
    control: (provided, state) => (
        console.log(state),
        {
        ...provided,
        minHeight: 30,

        borderColor: '#007cba',
        boxShadow: '0 0 0 1px #007cba',
        '&:hover, &:active, &:focus': {
            borderColor: '#007cba',

        }
    })
}

function ShareModal(props) {
    const {clientIds, type} = props;
    const {getBlocksByClientId, getEditorBlocks} = props;
    const [blockTitle, setBlockTitle] = useState('');
    const [description, setDescription] = useState('');
    const [category, setCategory] = useState(null);
    const [loading, setLoading] = useState(false);
    const [options, setOptions] = useState([]);
    const [blocksSelection, setBlocksSelection] = useState(null);

    useEffect(() => {
        const keyName = type === 'page' ? 'page_categories_list' : 'section_categories_list';
        const options = sortBy(getWithExpiry(keyName), 'label');
        setOptions(options);
    }, [type]);

    useEffect(() => {
        if (type === 'block') {
            if (clientIds) setBlocksSelection(getBlocksByClientId(clientIds));
        }
        if (type === 'page') {
            setBlocksSelection(getEditorBlocks());
        }
    }, [type, clientIds])

    const handleChange = (newValue, actionMeta) => {
        setCategory(map(newValue, 'value'));
    };

    const shareThisBlock = () => {
        if (loading) return;
        setLoading(true);
        apiFetch({
            path: 'redux/v1/templates/share/',
            method: 'POST',
            headers: {'Registered-Blocks': installedBlocksTypes()},
            data: {
                'postID': select('core/editor').getCurrentPostId(),
                'editor_blocks': blocksSelection,
                'title': blockTitle,
                'description': description,
                'type': type,
                'categories': category
            }
        }).then(data => {
            setLoading(false);
            if (data.success) {
                alert('Successfully shared your block!');
                window.open(data.data.url, '_blank');
            } else {
                alert('An unexpected error occured');
            }
            ModalManager.close();
        }).catch(err => {
            setLoading(false);
            alert('There was an error: ' + err);
            ModalManager.close();
        });
    }

    const onCloseWizard = () => {
        ModalManager.close();
    }

    if (!blocksSelection)
        return (
            <Modal compactMode={true}>
                <div className="redux-templates-share-modal-wrapper">
                    <div className="redux-templates-modal-header">
                        <h3>{__('Share Wizard', redux_templates.i18n)}</h3>
                        <button className="redux-templates-modal-close" onClick={onCloseWizard}>
                            <i className={'fas fa-times'}/>
                        </button>
                    </div>
                    <div className="redux-templates-share">
                        <div className="spinner-wrapper">
                            <Spinner />
                        </div>
                    </div>
                </div>
            </Modal>
        );
    return (
        <Modal compactMode={true}>
            <div className="redux-templates-share-modal-wrapper">
                <div className="redux-templates-modal-header">
                    <h3>{__('Share Wizard', redux_templates.i18n)}</h3>
                    <button className="redux-templates-modal-close" onClick={onCloseWizard}>
                        <i className={'fas fa-times'}/>
                    </button>
                </div>
                <div className="redux-templates-share">
                    <div className="panel">
                        <div className="input-panel">
                            <div className="field">
                                <label>Block Title</label>
                                <input type="text" value={blockTitle} onChange={(e) => setBlockTitle(e.target.value)} />
                            </div>
                            <div className="field">
                                <label>Category</label>
                                <CreatableSelect
                                    isMulti
                                    onChange={handleChange}
                                    options={options}
                                    width='200px'
                                    styles={customStyles}
                                />
                            </div>
                            <div className="field">
                                <label>Description</label>
                                <textarea value={description} onChange={(e) => setDescription(e.target.value)} />
                            </div>
                            <button className="button button-primary" onClick={shareThisBlock}>
                                {loading ? <i className="fas fa-spinner fa-pulse"/> : <i className="fas fa-share"></i>} Share this block
                            </button>
                        </div>
                        <div className="preview-panel">
                            <div className="redux-templates-block-preview-hover" />
                            <BlockPreview blocks={blocksSelection} />
                        </div>
                    </div>

                </div>
            </div>
        </Modal>
    );
}




export default compose([
    withSelect((select, props) => {
        const {getBlocksByClientId} = select('core/block-editor');
        const {getEditorBlocks} = select('core/editor');
        return {
            getBlocksByClientId,
            getEditorBlocks
        };
    })
])(ShareModal);
