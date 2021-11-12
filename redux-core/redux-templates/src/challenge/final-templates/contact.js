/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n'
import CONFIG from '../config';
import {CheckboxControl} from '@wordpress/components';

const {compose} = wp.compose;
const {useState} = wp.element;
const {withDispatch, withSelect} = wp.data;


const ratingStars = (
    <span className="rating-stars">
        <i className="fa fa-star"></i>
        <i className="fa fa-star"></i>
        <i className="fa fa-star"></i>
        <i className="fa fa-star"></i>
        <i className="fa fa-star"></i>
    </span>
);

function ChallengeContact(props) {
    const { setChallengeStep, setChallengeFinalStatus, setChallengeOpen } = props;
    const [comment, setComment] = useState('');
    const [agreeToContactFurther, setAgreement] = useState(false);
    const closeModal = () => {
        setChallengeStep(CONFIG.beginningStep);
        setChallengeFinalStatus('');
        setChallengeOpen(false);
    }

    const handleChange = (e) => {
        setComment(e.target.value);
    }

    const contactRedux = () => {
        //sending data
        console.log('contact information', comment, agreeToContactFurther);
        closeModal();
    }

    return (
        <div className="redux-templates-modal-overlay">
            <div className="redux-templates-modal-wrapper challenge-popup-wrapper">
                <div className="challenge-popup-header challenge-popup-header-contact"
                    style={{ backgroundImage: `url(${redux_templates.plugin + 'assets/img/popup-contact.png'})` }}>
                    <a className="challenge-popup-close" onClick={closeModal}>
                        <i className='fas fa-times' />
                    </a>
                </div>
                <div className="challenge-popup-content challenge-contact">
                    <h3>{__('Help us improve Redux', redux_templates.i18n)}</h3>
                    <p>
                        {__('We\'re sorry that it took longer than 5 minutes to try our challenge. We aim to ensure our Block Template library is as beginner friendly as possible. Please take a moment to let us know how we can improve our challenge.', redux_templates.i18n)}
                    </p>
                    <textarea value={comment} onChange={handleChange}></textarea>
                    <CheckboxControl
                        label={__('Yes, I give Redux permission to contact me for any follow up questions.', redux_templates.i18n)}
                        checked={agreeToContactFurther}
                        onChange={() => setAgreement(!agreeToContactFurther)}
                    />
                    <button className="challenge-popup-btn challenge-popup-rate-btn" onClick={contactRedux}>
                        {__('Submit Feedback', redux_templates.i18n)}
                    </button>
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
])(ChallengeContact);
