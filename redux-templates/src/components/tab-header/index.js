import {__} from '@wordpress/i18n';
import {compose} from '@wordpress/compose';
import {withDispatch, withSelect} from '@wordpress/data';
import {ModalManager} from '../../modal-manager';
import ChallengeDot from '~redux-templates/challenge/tooltip/ChallengeDot';
export function TabHeader(props) {
    const { activeItemType, searchContext, activeCollection, isChallengeOpen } = props;
    const { setActiveItemType, setSearchContext, setChallengeOpen, clearSearch } = props;

    const isActive = (itemType) => {
        return (activeItemType === itemType) ? 'active' : '';
    }

    const onSearchContextUpdate = (e) => {
        if (activeItemType !=='saved') setSearchContext(e.target.value);
    }

    const changeTab = (tabName) => {
        if (document.getElementById('modalContent')) document.getElementById('modalContent').scrollTop = 0;
        setActiveItemType(tabName);
    }

    const closeModal = () => {
        if (isChallengeOpen === false) {
            ModalManager.close();
        }
    }

    return (
        <div className="redux-templates-builder-modal-header">
            <div className="template-search-box">
                {
                    ((activeItemType !== 'collection'  || activeCollection === null) && activeItemType !== 'saved') &&
                    <div>
                        <input type="text" placeholder={__('Search for a template', redux_templates.i18n)} className="form-control" value={searchContext} onChange={onSearchContextUpdate} />
                        <ChallengeDot step={1} />
                        <i className="fas fa-search" />
                    </div>
                }
            </div>

            <div className="redux-templates-template-list-header" data-tut="tour__navigation">
                <button className={ isActive('section') } onClick={e => changeTab('section')}> {__('Sections', redux_templates.i18n)} </button>
                <button className={ isActive('page') } onClick={e => changeTab('page')}> {__('Templates', redux_templates.i18n)} </button>
                <button className={ isActive('collection') } onClick={e => changeTab('collection')}> {__('Template Kits', redux_templates.i18n)} </button>
                <button className={ isActive('saved') } onClick={e => changeTab('saved')}> {__('Saved', redux_templates.i18n)} </button>
                <ChallengeDot step={0} />
                <button className="redux-templates-builder-close-modal" onClick={closeModal} >
					<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" role="img" aria-hidden="true" focusable="false"><path d="M13 11.9l3.3-3.4-1.1-1-3.2 3.3-3.2-3.3-1.1 1 3.3 3.4-3.5 3.6 1 1L12 13l3.5 3.5 1-1z"></path></svg>
                </button>
            </div>
        </div>
    );
}

export default compose([
    withDispatch((dispatch) => {
        const {
            setActiveItemType,
            setSearchContext,
            clearSearch
        } = dispatch('redux-templates/sectionslist');

        return {
            setActiveItemType,
            setSearchContext,
            clearSearch
        };
    }),

    withSelect((select, props) => {
        const { getActiveItemType, getSearchContext, getActiveCollection, getChallengeOpen } = select('redux-templates/sectionslist');
        return { activeItemType: getActiveItemType(), searchContext: getSearchContext(), activeCollection: getActiveCollection(), isChallengeOpen: getChallengeOpen() };
    })

])(TabHeader);
