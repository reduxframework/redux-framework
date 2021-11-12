/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n'
import CONFIG from '../config';
import helper from '../helper';

const { compose } = wp.compose;
const { withDispatch, withSelect } = wp.data;


const ratingStars = (
    <span className="rating-stars">
        <i className="fa fa-star"></i>
        <i className="fa fa-star"></i>
        <i className="fa fa-star"></i>
        <i className="fa fa-star"></i>
        <i className="fa fa-star"></i>
    </span>
);

function ChallengeCongrats(props) {
    const {setChallengeStep, setChallengeFinalStatus, setChallengeOpen} = props;
    const closeModal = () => {
        setChallengeStep(CONFIG.beginningStep);
        setChallengeFinalStatus('');
        setChallengeOpen(false);
    }
    return (
        <div className="redux-templates-modal-overlay">
            <div className="redux-templates-modal-wrapper challenge-popup-wrapper">
                <div className="challenge-popup-header challenge-popup-header-congrats"
                    style={{backgroundImage: `url(${redux_templates.plugin + 'assets/img/popup-congrats.png'})`}}>
                    <a className="challenge-popup-close" onClick={closeModal}>
                        <i className='fas fa-times' />
                    </a>
                </div>
                <div className="challenge-popup-content">
                    <h3>{__( 'Congrats, you did it!', redux_templates.i18n )}</h3>
                    <p>
                        {__( 'You completed the Redux Challenge in ', redux_templates.i18n )}<b>{helper.getLocalizedDuration()}</b>.
                        {__('Share your success story with other Redux users and help us spread the word', redux_templates.i18n)}
                        <b>{__('by giving Redux a 5-star rating (', redux_templates.i18n)} {ratingStars}{__(') on WordPress.org', redux_templates.i18n)}</b>.
                        {__('Thanks for your support and we look forward to bringing more awesome features.', redux_templates.i18n)}
                    </p>
                    <a href="https://wordpress.org/support/plugin/redux-framework/reviews/?filter=5#new-post" className="challenge-popup-btn challenge-popup-rate-btn" target="_blank" rel="noopener">
                        {__( 'Rate Redux on Wordpress.org', redux_templates.i18n ) }
                        <span className="dashicons dashicons-external"></span>
                    </a>
                </div>
            </div>
        </div>
    );
}

export default compose([
    withDispatch((dispatch) => {
        const { setChallengeStep, setChallengeFinalStatus, setChallengeOpen } = dispatch('redux-templates/sectionslist');
        return {
            setChallengeStep,
            setChallengeFinalStatus,
            setChallengeOpen
        };
    })
])(ChallengeCongrats);
