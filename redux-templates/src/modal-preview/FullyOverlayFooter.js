const {__} = wp.i18n

function FullyOverlayFooter(props) {
    const {previewClass, expandedClass, pro} = props;
    const {onChangePreviewClass, onToggleExpanded, onImport} = props;
    const previewClassesList = [
        {className: 'preview-desktop', screenReaderText: 'Enter desktop preview mode'},
        {className: 'preview-tablet', screenReaderText: 'Enter tablet preview mode'},
        {className: 'preview-mobile', screenReaderText: 'Enter mobile preview mode'}
    ];

    const toggleExpanded = () => {
        let nextStatus = (expandedClass === 'collapsed') ? 'expanded' : 'collapsed';
        onToggleExpanded(nextStatus);
    }
    return (
        <div className="wp-full-overlay-footer">
            <div className="footer-import-button-wrap redux-templates-import-button-group">

                {
                    pro ?
                        <div className="action-buttons">
                            <a className="redux-templates-button-download" target="_blank" href="http://redux-templates.io/">
                                <i className="fas fa-upload"></i>&nbsp;{__('Upgrade to Pro', redux_templates.i18n)}
                            </a>
                        </div>
                        :
                        <a className="button button-hero hide-if-no-customize button-primary redux-templates-import"
                           onClick={onImport}>
                            <i className="fas fa-download"></i>&nbsp;{__('Import', redux_templates.i18n)}
                        </a>
                }

            </div>
            <button type="button" className="collapse-sidebar button" onClick={toggleExpanded} aria-expanded="true"
                    aria-label="Collapse Sidebar">
                <span className="collapse-sidebar-arrow"></span>
                <span className="collapse-sidebar-label">{__('Collapse', redux_templates.i18n)}</span>
            </button>

            <div className="devices-wrapper">
                <div className="devices">
                    {
                        previewClassesList.map((previewObject, i) => {
                            return (
                                <button type="button"
                                        className={previewClass === previewObject.className ? previewObject.className + ' active' : previewObject.className}
                                        aria-pressed="true" key={i}
                                        onClick={() => onChangePreviewClass(previewObject.className)}>
                                    <span className="screen-reader-text">{previewObject.screenReaderText}</span>
                                </button>
                            );
                        })
                    }
                </div>
            </div>

        </div>
    );
}

export default FullyOverlayFooter;
