import {__} from '@wordpress/i18n';
import {compose} from '@wordpress/compose';
import {withDispatch} from '@wordpress/data';
import {Notice} from '@wordpress/components';

import './style.scss';

export function ErrorNotice(props) {
    const {discardAllErrorMessages, errorMessages} = props;
    return (
        <div className='redux-templates-error-notice'>
            <Notice status="error" onRemove={discardAllErrorMessages}>
                <p>
                    {
                        errorMessages.join(', ')
                    }
                </p>
            </Notice>
        </div>
    );

}


export default compose([
    withDispatch((dispatch) => {
        const {
            discardAllErrorMessages
        } = dispatch('redux-templates/sectionslist');

        return {
            discardAllErrorMessages
        };
    })
])(ErrorNotice);
