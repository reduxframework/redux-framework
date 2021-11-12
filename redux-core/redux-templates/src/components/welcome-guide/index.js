/**
 * WordPress dependencies
 */
const { useState } = wp.element;
import { useSelect } from '@wordpress/data';
import { ExternalLink, Guide } from '@wordpress/components';
const {apiFetch} = wp;
import { __ } from '@wordpress/i18n';

/**
 * Internal dependencies
 */
import {
	GuideImage1,
	GuideImage2,
	GuideImage3,
} from './images';
import './style.scss';

export default function WelcomeGuide() {

	const [ isOpen, setIsOpen ] = useState( true );

	const isActive = useSelect(
		( select ) =>
			select( 'core/edit-post' ).isFeatureActive( 'welcomeGuide' ),
		[]
	);

	if ( isActive ) { // Don't want to show during the WP guide.
		delete redux_templates.welcome; // In fact, we don't want to show it until the next page load!
		return null;
	}

	if ( ! isOpen || 'undefined' === typeof( redux_templates.welcome ) ) {
		return null;
	}

	return (
		<Guide
			className="redux-edit-post-welcome-guide"
			contentLabel={ __( 'Say hello to the Redux template library', redux_templates.i18n ) }
			onFinish={ () => {
				setIsOpen( false );
				const options = {
					method: 'POST',
					path: 'redux/v1/templates/welcome/?uid=' + window.userSettings.uid,
				}
				apiFetch(options).then(response => {
				}).catch(error => {
				});
			} }
			pages={ [
				{
					image: <GuideImage1 />,
					content: (
						<>
							<h1 className="redux-edit-post-welcome-guide__heading">
								{ __( 'Try the Redux Template Library', redux_templates.i18n ) }
							</h1>
							<h3 className="redux-edit-post-welcome-guide__text">
								{ __(
									'Redux brings you over 1,000 importable templates and blocks that allow you to build Gutenberg powered pages and websites in minutes not days.',
									redux_templates.i18n
								) }
							</h3>
						</>
					),
				},
				{
					image: <GuideImage2 />,
					content: (
						<>
							<h1 className="redux-edit-post-welcome-guide__heading">
								{ __( 'Using the Template Library', redux_templates.i18n ) }
							</h1>
							<h3 className="redux-edit-post-welcome-guide__text">
								{ __(
									'To use the template library click on the library button then pick your favourite template and import! Redux allows you to import beautiful Gutenberg pages in seconds.',
									redux_templates.i18n
								) }
							</h3>
						</>
					),
				},
				{
					image: <GuideImage3 />,
					content: (
						<>
							<h1 className="redux-edit-post-welcome-guide__heading">
								{ __( 'Import 5 templates for free or go Pro!', redux_templates.i18n ) }
							</h1>
							<h3 className="redux-edit-post-welcome-guide__text">
								{ __(
									'Redux allows you 5 free imports or you can go Pro now and import unlimited templates for just $49/year (limited time only).',
									redux_templates.i18n
								) }
								<br /><br />
								<center>
									<ExternalLink href={ `${ redux_templates.u }welcome-guide` }>
										{ __( 'Learn more at Redux.io', redux_templates.i18n ) }
									</ExternalLink>
								</center>
							</h3>
						</>
					),
				}
			] }
		/>
	);
}
