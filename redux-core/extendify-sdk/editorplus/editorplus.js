// Quick method to hide the title if the template is active
if (window._wpLoadBlockEditor) {
    const finished = window.wp.data.subscribe(() => {
        const epTemplateSelected =
            window.wp.data
                .select('core/editor')
                .getEditedPostAttribute('template') ===
            'editorplus-template.php'
        const title = document.querySelector(
            '.edit-post-visual-editor__post-title-wrapper',
        )
        const wrapper = document.querySelector('.editor-styles-wrapper')

        // Too early
        if (!title || !wrapper) return

        if (epTemplateSelected) {
            // GB needs to compute the height first
            Promise.resolve().then(() => (title.style.display = 'none'))
            wrapper.style.paddingTop = '0'
            wrapper.style.backgroundColor = '#ffffff'
        } else {
            title.style.removeProperty('display')
            wrapper.style.removeProperty('padding-top')
            wrapper.style.removeProperty('background-color')
        }
        finished()
    })
}
