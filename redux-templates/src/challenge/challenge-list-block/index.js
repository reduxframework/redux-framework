/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n'
import ChallengeStepItem from './ChallengeStepItem';
import ProgressBar from './ProgressBar';
import CONFIG from '../config';
import './style.scss'

const {compose} = wp.compose;
const {withDispatch, withSelect} = wp.data;
const {useState, useEffect} = wp.element;

function ChallengeListBlock(props) {
    const {started, onStarted} = props;
    const {challengeStep, finalStatus, setChallengeOpen, setChallengeStep} = props;
    const [buttonRowClassname, setButtonRowClassname] = useState('challenge-button-row');
    useEffect(() => {
        setButtonRowClassname(challengeStep !== CONFIG.beginningStep  ? 'challenge-button-row started' : 'challenge-button-row');
    }, [challengeStep])
    
    const onCancelChallenge = () => {
        setChallengeOpen(false);
        setChallengeStep(-1);
    }

    return (
        <div className='challenge-list-block'>
            <p>{__('Complete the challenge and get up and running within 5 minutes', redux_templates.i18n)}</p>
            <ProgressBar currentStep={finalStatus === 'success' ?  CONFIG.totalStep : challengeStep} />
            <ul className='challenge-list'>
                {
                    CONFIG.list.map((item, i) => {
                        return (<ChallengeStepItem key={i} step={i} currentStep={challengeStep} finalStatus={finalStatus} caption={item.caption} />);
                    })
                }
            </ul>
            { finalStatus === '' &&
                <div className={buttonRowClassname}>
                    {challengeStep === CONFIG.beginningStep && 
                        <button className='btn-challenge-start' onClick={onStarted}>{__('Start Challenge', redux_templates.i18n)}</button>}
                    {challengeStep === CONFIG.beginningStep && <button className='btn-challenge-skip' onClick={onCancelChallenge}>{__('Skip Challenge', redux_templates.i18n)}</button>}
                    {challengeStep !== CONFIG.beginningStep && <button className='btn-challenge-cancel' onClick={onCancelChallenge}>{__('Cancel Challenge', redux_templates.i18n)}</button>}
                </div>
            }
        </div>
    );

}


export default compose([
    withDispatch((dispatch) => {
        const {setChallengeOpen, setChallengeStep} = dispatch('redux-templates/sectionslist');
        return {
            setChallengeOpen,
            setChallengeStep
        };
    }),

    withSelect((select) => {
        const {getChallengeStep, getChallengeFinalStatus} = select('redux-templates/sectionslist');
        return {
            challengeStep: getChallengeStep(),
            finalStatus: getChallengeFinalStatus()
        };
    })
])(ChallengeListBlock);
