const { Fragment } = wp.element;
import Sidebar from '../sidebar';
import TemplateListSubHeader from '~redux-templates/components/template-list-subheader';
import TemplateList from '../view-template-list';

export default function WithSidebarCollection (props) {
    return (
        <Fragment>
            <div id="redux-templates-collection-modal-sidebar" className="redux-templates-collection-modal-sidebar">
                <Sidebar />
            </div>
            <div className="redux-templates-collection-modal-content-area" data-tut="tour__main_body" id="modalContent">
                <TemplateListSubHeader />
                <TemplateList />
            </div>
        </Fragment>
    );
}
