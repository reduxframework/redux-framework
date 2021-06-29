import FullyOverlayHeader from './FullyOverlayHeader';
import SidebarContent from './SidebarContent';
import FullyOverlayFooter from './FullyOverlayFooter';
import {isBlockPro} from '../stores/helper';

function SitePreviewSidebar(props) {
    const {itemData, previewClass, expandedClass, onImport} = props;
    const {onCloseCustomizer, onChangePreviewClass, onToggleExpanded, onNextBlock, onPrevBlock} = props;
    const isPro = isBlockPro(itemData.pro, itemData.source);

    return (
        <div className="wp-full-overlay-sidebar">
            <FullyOverlayHeader onCloseCustomizer={onCloseCustomizer} onNextBlock={onNextBlock}
                                onPrevBlock={onPrevBlock}
                                pro={isPro} onImport={onImport}/>
            <SidebarContent itemData={itemData} pro={isPro} />
            <FullyOverlayFooter previewClass={previewClass} expandedClass={expandedClass} pro={isPro}
                                onChangePreviewClass={onChangePreviewClass} onToggleExpanded={onToggleExpanded}
                                onImport={onImport}/>
        </div>
    );
}


export default SitePreviewSidebar;
