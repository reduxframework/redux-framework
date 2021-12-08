import {__} from '@wordpress/i18n'
import CONFIG from './config';
export default {

    /**
     * Get number of seconds left to complete the Challenge.
     */
    getSecondsLeft: function() {
        var secondsLeft = localStorage.getItem( 'reduxChallengeSecondsLeft' );

        secondsLeft = isNaN(secondsLeft) || secondsLeft == null ? CONFIG.initialSecondsLeft : parseInt( secondsLeft, 10 );

        return secondsLeft;
    },

    /**
     * Save number of seconds left to complete the Challenge.
     */
    saveSecondsLeft: function( secondsLeft ) {

        localStorage.setItem( 'reduxChallengeSecondsLeft', secondsLeft );
    },

    /**
     * Get 'minutes' part of timer display.
     */
    getMinutesFormatted: function( secondsLeft ) {
        return Math.floor( secondsLeft / 60 );
    },

    /**
     * Get 'seconds' part of timer display.
     */
    getSecondsFormatted: function( secondsLeft ) {
        return secondsLeft % 60;
    },

    /**
     * Get formatted timer for display.
     */
    getFormatted: function( secondsLeft ) {

        if (secondsLeft < 0) return '0:00';

        var timerMinutes = this.getMinutesFormatted( secondsLeft );
        var timerSeconds = this.getSecondsFormatted( secondsLeft );

        return timerMinutes + ( 9 < timerSeconds ? ':' : ':0' ) + timerSeconds;
    },

    /**
     * Get Localized time string for display
     */
    getLocalizedDuration: function() {
        let secondsLeft = this.getSecondsLeft();
        secondsLeft = CONFIG.initialSecondsLeft - secondsLeft;

        var timerMinutes = this.getMinutesFormatted( secondsLeft );
        var timerSeconds = this.getSecondsFormatted( secondsLeft );

        const minutesString = timerMinutes ? timerMinutes + ' ' + __( 'minutes', redux_templates.i18n ) + ' ' : '';
        const secondsString = timerSeconds ? timerSeconds + ' ' + __( 'seconds', redux_templates.i18n ) : '';
        return minutesString + secondsString;
    },

    /**
     * Get last saved step.
     */
    loadStep: function() {

        var step = localStorage.getItem( 'reduxChallengeStep' );
        step = isNaN(step) ? -1 : parseInt( step, 10 );

        return step;
    },

    /**
     * Save Challenge step.
     */
    saveStep: function( step ) {
        localStorage.setItem( 'reduxChallengeStep', step );
    },
};
