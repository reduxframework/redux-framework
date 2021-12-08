const {__} = wp.i18n

function FullyOverlayHeader(props) {
    const {onCloseCustomizer, onNextBlock, onPrevBlock, onImport, pro} = props;
    return (
        <div className="wp-full-overlay-header">
            <button className="close-full-overlay" onClick={onCloseCustomizer}>
                <span className="screen-reader-text">{__('Close', redux_templates.i18n)}</span>
            </button>
            <button className="previous-theme" onClick={onPrevBlock}>
                <span className="screen-reader-text">{__('Previous', redux_templates.i18n)}</span>
            </button>
            <button className="next-theme" onClick={onNextBlock}>
                <span className="screen-reader-text">{__('Next', redux_templates.i18n)}</span>
            </button>
            {
                pro === false &&
                <a className="button hide-if-no-customize button-primary redux-templates-section-import" onClick={onImport}
                data-import="disabled">
                    {__('Import', redux_templates.i18n)}
                </a>
            }
        </div>
    );
}

export default FullyOverlayHeader;
