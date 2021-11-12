const {compose} = wp.compose;
const {withSelect} = wp.data;
import {useEffect} from '@wordpress/element';

function TemplateChange (props) {
	const {template} = props;
	useEffect(() => {
		if ( template.includes('redux-templates_') ) {
			document.body.className += ' redux-template';
		} else {
			document.querySelector('body').classList.remove('redux-template');
		}
	}, [template])
	return ( <div /> )
}

export default compose([
	withSelect((select) => {
		const {getEditedPostAttribute} = select('core/editor');
		return {template: getEditedPostAttribute('template')};
	})
])(TemplateChange);
