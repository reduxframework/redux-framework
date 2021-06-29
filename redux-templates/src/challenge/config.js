import {__} from '@wordpress/i18n'
import {animateScroll} from 'react-scroll';
import {dispatch, select} from '@wordpress/data';
const {setTourActiveButtonGroup, setImportingTemplate} = dispatch('redux-templates/sectionslist');
const {getPageData} = select('redux-templates/sectionslist');
import {ModalManager} from '~redux-templates/modal-manager';
import PreviewModal from '~redux-templates/modal-preview';
export default {
    initialSecondsLeft: 300,
    beginningStep: -1,
    totalStep: 7,
    list: [
        {
            selector: '[data-tut="tour__navigation"]',
            caption: __('Template Type Tabs', redux_templates.i18n),
            offset: {
                x: 0,
                y: 50,
                arrowX: 0,
                arrowY: -20
            },
            box: {
                width: 250
            },
            direction: 'top',
            content: () => (
                <div>
                    {__('These are the different types of templates we have.', redux_templates.i18n)}
                    <ul>
                        <li>
                            <strong>{__('Sections', redux_templates.i18n)}</strong>
                            {__(' are the building blocks of a page. Each "row" of content on a page we consider a section.', redux_templates.i18n)}
                        </li>
                        <li>
                            <strong>{__('Pages', redux_templates.i18n)}</strong>
                            {__(' are, you guessed it, a group of multiple sections making up a page.', redux_templates.i18n)}
                        </li>
                        <li>
                            <strong>{__('Template Kits', redux_templates.i18n)}</strong>
                            {__(' are groups of pages that all follow a style or theme.', redux_templates.i18n)}
                        </li>
                        <li>
                            <strong>{__('Saved', redux_templates.i18n)}</strong>
                            {__(' are reusable blocks that you may have previously saved for later.', redux_templates.i18n)}
                        </li>
                    </ul>
                </div>
            )
        },
        {
            selector: '[data-tut="tour__filtering"]',
            caption: __('Sidebar', redux_templates.i18n),
            content: __('This area is where you can search and filter to find the right kind of templates you want.', redux_templates.i18n),
            direction: 'left',
            offset: {
                x: 40,
                y: 10,
                arrowX: -20,
                arrowY: 0
            },
            box: {
                width: 250,
                height: 130
            },
            action: () => {
                animateScroll.scrollToTop({
                    containerId: 'redux-templates-collection-modal-sidebar',
                    duration: 0,
                });
            },
        },
        {
            selector: '[data-tut="tour__filtering"]',
            caption: __('Plugins Filter', redux_templates.i18n),
            offset: {
                x: 40,
                y: 10,
                arrowX: -20,
                arrowY: 0
            },
            box: {
                width: 290,
                height: 185
            },
            content: () => (
                <div>
                    {__('Some templates require certain plugins. You can filter or select those templates. Hint, if the text is a ', redux_templates.i18n)}
                    <a href="#" className="missing-dependency">{__('little orange', redux_templates.i18n)}</a>
                    {__(', you don`t have that plugin installed yet, but don`t worry. Redux will help you with that too.', redux_templates.i18n)}
                </div>
            ),
            action: () => {
                animateScroll.scrollToBottom({
                    containerId: 'redux-templates-collection-modal-sidebar',
                    duration: 0,
                });
            },
            direction: 'left'
        },
        {
            selector: '[data-tut="tour__main_body"]',
            caption: __('Templates List', redux_templates.i18n),
            content: __('This area is where the templates will show up that match the filters you\'ve selected. You can click on many of them to preview or import them.', redux_templates.i18n),
            direction: 'left',
            offset: {
                x: 40,
                y: 10,
                arrowX: -20,
                arrowY: 0
            },
            box: {
                width: 250,
                height: 150
            },
            action: () => {
                animateScroll.scrollToTop({
                    containerId: 'redux-templates-collection-modal-sidebar',
                    duration: 0,
                });
                setTourActiveButtonGroup(null);
            }
        },
        {
            selector: '#modalContainer .redux-templates-single-item-inner:first-child',
            caption: __('Template Hover', redux_templates.i18n),
            content: __('When you hover over a template you can see via icons what plugins are required for this template. You can then choose to Preview or Import a design.', redux_templates.i18n),
            action: () => {
                ModalManager.closeCustomizer();
                const pageData = getPageData();
                if (pageData && pageData.length > 0) {
                    setTourActiveButtonGroup(pageData[0])
                }
            },
            direction: 'left',
            offset: {
                x: 40,
                y: 10,
                arrowX: -20,
                arrowY: 0
            },
            box: {
                width: 240,
                height: 169
            },
        },
        {
            selector: '.wp-full-overlay-sidebar',
            caption: __('Preview Dialog', redux_templates.i18n),
            content: __('This is the preview dialog. It gives more details about the template and helps you to see what you could expect the templates to look like.', redux_templates.i18n),
            action: () => {
                setTourActiveButtonGroup(null);
                setImportingTemplate(null);
                const pageData = getPageData();
                if (pageData && pageData.length > 0) {
                    ModalManager.openCustomizer(
                        <PreviewModal startIndex={0} currentPageData={pageData}/>
                    )
                }
            },
            position: 'center'
        },
        {
            selector: '.redux-templates-import-wizard-wrapper',
            caption: __('Import Wizard', redux_templates.i18n),
            content: __('When you click to import a template, sometimes you will be missing one of the required plugins. Redux will do its best to help you install what\'s missing. If some of them are premium plugins, you will be provided details on where you can get them.', redux_templates.i18n),
            direction: 'right',
            offset: {
                x: 0,
                y: 85,
                arrowX: 40,
                arrowY: 25
            },
            box: {
                width: 250,
                height: 169
            },
            action: () => {
                // if (ModalManager.isModalOpened() === false) ModalManager.open(<LibraryModal autoTourStart={false} />)
                if (document.getElementsByClassName('tooltipster-box'))
                    document.getElementsByClassName('tooltipster-box')[0].style.display = 'none';
                ModalManager.show();
                ModalManager.closeCustomizer();
                const pageData = getPageData();
                if (pageData && pageData.length > 0) setImportingTemplate(pageData[0]);
                setTimeout(() => {
                    const openedPanel = document.getElementsByClassName('redux-templates-modal-wrapper');
                    if (openedPanel && openedPanel.length > 0) {
                        let openPanel = openedPanel[0].getBoundingClientRect();
                        let box = {top: openPanel.top + 90, left: openPanel.left - 320};
                        dispatch('redux-templates/sectionslist').setChallengeTooltipRect(box);
                    }
                    if (document.getElementsByClassName('tooltipster-box'))
                        document.getElementsByClassName('tooltipster-box')[0].style.display = 'block';
                }, 0)
            }
        }
    ]
};
