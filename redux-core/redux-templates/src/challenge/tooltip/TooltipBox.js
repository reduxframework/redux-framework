import {__} from '@wordpress/i18n';

const { compose } = wp.compose;
const { withDispatch, withSelect } = wp.data;
const { useState, useEffect } = wp.element;
import {ModalManager} from '~redux-templates/modal-manager';
import CONFIG from '../config';
import helper from '../helper';
const ARROW_BOX = 30;
const DEFAULT_BOX_WIDTH = 250;
const DEFAULT_BOX_HEIGHT = 300;
const DEFAULT_OFFSET_X = 0;
const DEFAULT_OFFSET_Y = 20;
const DEFAULT_ARROW_OFFSET_X = 20;
const DEFAULT_ARROW_OFFSET_Y = 20;
function TooltipBox(props) {
    const { challengeStep, tooltipRect, isOpen, setChallengeStep, setChallengeFinalStatus, setChallengePassed, setChallengeListExpanded, setImportingTemplate } = props;
    const [style, setStyle] = useState({});
    const [arrowStyle, setArrowStyle] = useState({});
    const [content, setContent] = useState('');
    const [wrapperClassname, setWrapperClassname] = useState('');

    const isVisible = () => {
        return ((challengeStep >= 0 || challengeStep > CONFIG.totalStep) && isOpen);
    }

    const calculateWithStepInformation = () => {
        const stepInformation = CONFIG.list[challengeStep];
        const boxWidth = (stepInformation.box && stepInformation.box.width) ? stepInformation.box.width : DEFAULT_BOX_WIDTH;
        const boxHeight = (stepInformation.box && stepInformation.box.height) ? stepInformation.box.height : DEFAULT_BOX_HEIGHT;
        const offsetX = stepInformation.offset ? stepInformation.offset.x :DEFAULT_OFFSET_X;
        const offsetY = stepInformation.offset ? stepInformation.offset.y :DEFAULT_OFFSET_Y;
        switch(stepInformation.direction) {
            case 'right':
                return [tooltipRect.left + offsetX, tooltipRect.top + offsetY - boxHeight / 2];
            case 'left':
                return [tooltipRect.left + offsetX, tooltipRect.top + offsetY - boxHeight / 2];
            case 'top':
                return [tooltipRect.left + offsetX - boxWidth / 2, tooltipRect.top + offsetY ];
            case 'bottom':
                return [tooltipRect.left + offsetX - boxWidth / 2, tooltipRect.top - boxHeight + offsetY];
            default:
                return [tooltipRect.left + offsetX, tooltipRect.top + offsetY];
        }
    }

    const calculateArrowOffset = () => {
        const stepInformation = CONFIG.list[challengeStep];
        const boxWidth = (stepInformation.box && stepInformation.box.width) ? stepInformation.box.width : DEFAULT_BOX_WIDTH;
        const boxHeight = (stepInformation.box && stepInformation.box.height) ? stepInformation.box.height : DEFAULT_BOX_HEIGHT;
        const arrowOffsetX = (stepInformation.offset && isNaN(stepInformation.offset.arrowX) === false) ? stepInformation.offset.arrowX : DEFAULT_ARROW_OFFSET_X;
        const arrowOffsetY = (stepInformation.offset && isNaN(stepInformation.offset.arrowY) === false) ? stepInformation.offset.arrowY : DEFAULT_ARROW_OFFSET_Y;
        switch(stepInformation.direction) {
            case 'top':
                return [boxWidth / 2 + arrowOffsetX, arrowOffsetY];
            case 'bottom':
                return [boxWidth / 2 + arrowOffsetX, arrowOffsetY];
            case 'left':
                return [arrowOffsetX, arrowOffsetY + boxHeight / 2 - ARROW_BOX / 2];
            case 'right':
                return [boxWidth + arrowOffsetX, arrowOffsetY + boxHeight / 2 - ARROW_BOX / 2];
            default:
                return [arrowOffsetX, arrowOffsetY];
        }
    }
    // adjust position and content upon steps change
    useEffect(() => {
        if (isVisible() && tooltipRect) {
            const stepInformation = CONFIG.list[challengeStep];
            if (stepInformation) {
                const [boxLeft, boxTop] = calculateWithStepInformation();
                const [arrowOffsetX, arrowOffsetY] = calculateArrowOffset();
                setStyle({
                    ...style,
                    display: 'block',
                    width: stepInformation.box ? stepInformation.box.width : DEFAULT_BOX_WIDTH,
                    left: boxLeft,
                    top: boxTop//tooltipRect.top + offsetY + PADDING_Y + ARROW_HEIGHT
                });
                setContent(stepInformation.content);
                setArrowStyle({
                    ...arrowStyle,
                    display: 'block',
                    left: boxLeft + arrowOffsetX,  // calculateLeftWithStepInformation(),
                    top: boxTop + arrowOffsetY // tooltipRect.top + offsetY + PADDING_Y
                });
            }
        } else {
            setStyle({ ...style, display: 'none' });
            setArrowStyle({...arrowStyle, display: 'none'});
        }
    }, [JSON.stringify(tooltipRect), challengeStep, isOpen]);

    // update wrapper class name based on step change
    useEffect(() => {
        const stepInformation = CONFIG.list[challengeStep];
        if (stepInformation) {
            switch(stepInformation.direction) {
                case 'top':
                    setWrapperClassname('challenge-tooltip tooltipster-sidetip tooltipster-top');
                    break;
                case 'bottom':
                    setWrapperClassname('challenge-tooltip tooltipster-sidetip tooltipster-bottom');
                    break;
                case 'left':
                    setWrapperClassname('challenge-tooltip tooltipster-sidetip tooltipster-left');
                    break;
                case 'right':
                    setWrapperClassname('challenge-tooltip tooltipster-sidetip tooltipster-right');
                    break;
                default:
                    setWrapperClassname('challenge-tooltip tooltipster-sidetip tooltipster-left');
            }

        }
    }, [challengeStep])

    const toNextStep = () => {
        if (challengeStep === CONFIG.totalStep - 1) {
            // finalize challenge
            ModalManager.show();
            setChallengeFinalStatus((helper.getSecondsLeft() > 0) ? 'success' : 'contact');
            setChallengeStep(CONFIG.beginningStep);
            setChallengePassed(true);
            setChallengeListExpanded(true);
            setImportingTemplate(null);
        } else
            setChallengeStep(challengeStep + 1);
    }


    return (
        <div className={wrapperClassname}>
            <div className="tooltipster-box" style={style}>
                {content}
                <div className="btn-row">
                    <button className="challenge-done-btn" onClick={toNextStep}>{__('Next', redux_templates.i18n)}</button>
                </div>
            </div>
            <div className="tooltipster-arrow" style={arrowStyle}>
                <div className="tooltipster-arrow-uncropped">
                    <div className="tooltipster-arrow-border"></div>
                    <div className="tooltipster-arrow-background"></div>
                </div>
            </div>
        </div>
    );
}


export default compose([
    withDispatch((dispatch) => {
        const { setChallengeStep, setChallengeFinalStatus, setChallengePassed, setChallengeListExpanded, setImportingTemplate } = dispatch('redux-templates/sectionslist');
        return {
            setChallengeStep,
            setChallengeFinalStatus,
            setChallengePassed,
            setChallengeListExpanded,
            setImportingTemplate
        };
    }),

    withSelect((select, props) => {
        const { getChallengeTooltipRect, getChallengeOpen, getChallengeStep, getChallengeFinalStatus } = select('redux-templates/sectionslist');
        return {
            tooltipRect: getChallengeTooltipRect(),
            isOpen: getChallengeOpen(),
            challengeStep: getChallengeStep(),
            finalStatus: getChallengeFinalStatus()
        };
    })
])(TooltipBox);
