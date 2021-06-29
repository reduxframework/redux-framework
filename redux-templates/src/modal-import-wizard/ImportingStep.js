import React from 'react';
const {useState, useEffect, useRef} = wp.element;
const {Spinner} = wp.components;
import TextTransition, { presets } from 'react-text-transition';
const {__} = wp.i18n

const MESSAGE_DELAY_MILLISECONDS = 4000;

const MESSAGES_LIST = [
    __('Please wait while your template is prepared.', redux_templates.i18n),
    __('Fetching the template.', redux_templates.i18n),
    __('We\'re getting closer now.', redux_templates.i18n),
    __('Wow, this is taking a long time.', redux_templates.i18n),
    __('Gah, this should be done by now!', redux_templates.i18n),
    __('Really, this should be done soon.', redux_templates.i18n),
    __('Are you sure your internet is working?!', redux_templates.i18n),
    __('Give up, it looks like it didn\'t work...', redux_templates.i18n),
];

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

export default function ImportingStep(props) {
    const [messageIndex, setMessageIndex] = useState(0);
    const [loadingMessage, setLoadingMessage] = useState(MESSAGES_LIST[0]);

    useInterval(() => {
        if (messageIndex === MESSAGES_LIST.length) return;
        setMessageIndex(messageIndex => messageIndex + 1);
        setLoadingMessage([MESSAGES_LIST[messageIndex + 1]]);
    }, MESSAGE_DELAY_MILLISECONDS)

    return (
        <div className="redux-templates-modal-body">
            <div className="redux-templates-import-wizard-spinner-wrapper">
                <TextTransition
                    text={loadingMessage}
                    springConfig={presets.gentle}
                />
                <Spinner/>
            </div>
        </div>
    );
};
