/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n'
import './style.scss'
import helper from './helper';
import CONFIG from './config';
import ChallengeListBlock from './challenge-list-block';
import ChallengeTimer from './challenge-timer';

const {compose} = wp.compose;
const {withDispatch, withSelect} = wp.data;
const {useState, useEffect} = wp.element;

function ReduxChallenge(props) {
    const {autoChallengeStart} = props;
    const {isOpen, challengeStep, setChallengeStep, listExpanded} = props;
    const [challengeClassname, setChallengeClassname] = useState('redux-templates-challenge');
    const [started, setStarted] = useState(false);

    useEffect(() => {
        if (challengeStep !== CONFIG.beginningStep && isOpen) {
            setChallengeClassname('redux-templates-challenge started')
            setStarted(true);
        }
    }, [challengeStep, isOpen]);

    const onStarted = () => {
        setChallengeStep(0);
        setStarted(true);
    }

    return (
        <div className={challengeClassname} style={{display: isOpen ? 'block' : 'none'}}>
            { listExpanded && <ChallengeListBlock onStarted={onStarted} /> }
            <ChallengeTimer started={started} />
        </div>
    );

}


export default compose([
    withDispatch((dispatch) => {
        const {setChallengeStep} = dispatch('redux-templates/sectionslist');
        return {
            setChallengeStep
        };
    }),

    withSelect((select) => {
        const {getChallengeStep, getChallengeOpen, getChallengeListExpanded} = select('redux-templates/sectionslist');
        return {
            challengeStep: getChallengeStep(),
            isOpen: getChallengeOpen(),
            listExpanded: getChallengeListExpanded()
        };
    })
])(ReduxChallenge);
