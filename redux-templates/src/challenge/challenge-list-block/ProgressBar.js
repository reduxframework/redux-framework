const {useState, useEffect, memo} = wp.element;
import CONFIG from '../config';
export default memo(function ProgressBar({currentStep}){
    const [width, setWidth] = useState(0);
    useEffect(() => {
        setWidth( currentStep <= 0 ? 0 : (currentStep / CONFIG.totalStep * 100) );
    }, [currentStep])
    return (
        <div className='challenge-bar'>
            <div style={{width: width + '%'}}></div>
        </div>
    );
});