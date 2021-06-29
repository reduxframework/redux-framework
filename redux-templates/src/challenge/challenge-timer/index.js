/**
 * WordPress dependencies
 */
import {__} from '@wordpress/i18n'
import './style.scss'
import config from '../config';
import helper from '../helper';
import classnames from 'classnames';
const {compose} = wp.compose;
const {withSelect, withDispatch} = wp.data;
const {useState, useEffect, useRef} = wp.element;

function useInterval(callback, delay) {
    const savedCallback = useRef();

    // Remember the latest callback.
    useEffect(() => {
        savedCallback.current = callback;
    }, [callback]);

    // Set up the interval.
    useEffect(() => {
        function tick() {
            savedCallback.current();
        }
        if (delay !== null) {
            let id = setInterval(tick, delay);
            return () => clearInterval(id);
        }
    }, [delay]);
}

function ChallengeTimer(props) {
    const {started, expanded, setChallengeListExpanded, isChallengeOpen, finalStatus} = props;
    const [secondsLeft, setSecondsLeft] = useState(helper.getSecondsLeft());
    const [paused, setPaused] = useState(false);

    // only timer
    useEffect(() => {
        window.addEventListener('focus', resume);
        window.addEventListener('blur', pause);
        return () => {
            window.removeEventListener('focus', resume);
            window.removeEventListener('blur', pause);
        };
    });

    // setup timer
    useEffect(() => {
        setSecondsLeft(helper.getSecondsLeft());
        if (helper.loadStep() === -1) {
            setSecondsLeft(config.initialSecondsLeft);
        }
    }, [isChallengeOpen]);

    // run timer
    useInterval(() => {
        setSecondsLeft(secondsLeft < 0 ? 0 : secondsLeft - 1);
        helper.saveSecondsLeft(secondsLeft < 0 ? 0 : secondsLeft - 1);
    }, (started && (paused === false) && secondsLeft >= 0 && finalStatus === '') ? 1000 : null);


    // Pause the timer.
    const pause = () => {
        setPaused(true);
    }

    // Resume the timer.
    const resume = () => {
        setPaused(false);
    }

    return (
        <div className='block-timer'>
            <div>
                <h3>{__('Redux Challenge', redux_templates.i18n)}</h3>
                <p><span>{helper.getFormatted(secondsLeft)}</span>{__(' remaining', redux_templates.i18n)}</p>
            </div>
            <div className={classnames('caret-icon', {'closed': expanded})} onClick={() => setChallengeListExpanded(!expanded)}>
                <i className="fa fa-caret-down"></i>
            </div>
        </div>
    );

}


export default compose([
    withDispatch((dispatch) => {
        const {setChallengeListExpanded} = dispatch('redux-templates/sectionslist');
        return {
            setChallengeListExpanded
        };
    }),
    withSelect((select) => {
        const {getChallengeOpen, getChallengeFinalStatus, getChallengeListExpanded} = select('redux-templates/sectionslist');
        return {
            isChallengeOpen: getChallengeOpen(),
            finalStatus: getChallengeFinalStatus(),
            expanded: getChallengeListExpanded()
        };
    })
])(ChallengeTimer);
