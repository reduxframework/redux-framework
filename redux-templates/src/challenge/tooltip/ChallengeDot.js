import {compose} from '@wordpress/compose';
import {withDispatch, withSelect} from '@wordpress/data';
import CONFIG from '../config';
const {findDOMNode, useRef, useEffect} = wp.element;
function ChallengeDot(props) {
    const {step, challengeStep, isOpen, setChallengeTooltipRect} = props;
    const selectedElement = useRef(null);
    useEffect(() => {
        window.addEventListener('resize', onResize);
        return () => {
            window.removeEventListener('resize', onResize);
        };
    }, [])

    useEffect(() => {
        if (isOpen === false) return;
        const stepInformation = CONFIG.list[challengeStep];
        if (stepInformation && stepInformation.action && typeof stepInformation.action === 'function') {
            stepInformation.action();
            onResize();
            setTimeout(onResize, 0);
        } else
            onResize();
    }, [challengeStep, isOpen]);

    const isVisible = () => {
        return ((challengeStep >= 0 && challengeStep < CONFIG.totalStep) && isOpen);
    }

    const onResize = () => {
        const box = getElementBounding();
        if (box) setChallengeTooltipRect(box);
    };

    const getElementBounding = () => {
        if (selectedElement && selectedElement.current) {
            const rect = findDOMNode(selectedElement.current).getBoundingClientRect();
            return {left: rect.left, top: rect.top, width: rect.width, height: rect.height};
        }
        return null;
    }
    if (isVisible() && challengeStep === step)
        return <i className="challenge-dot tooltipstered" ref={selectedElement}>
            &nbsp;
        </i>;
    return null;
}


export default compose([
    withDispatch((dispatch) => {
        const {setChallengeTooltipRect} = dispatch('redux-templates/sectionslist');
        return {
            setChallengeTooltipRect
        };
    }),
    withSelect((select, props) => {
        const { getChallengeOpen, getChallengeStep } = select('redux-templates/sectionslist');
        return {
            isOpen: getChallengeOpen(),
            challengeStep: getChallengeStep()
        };
    })
])(ChallengeDot);
