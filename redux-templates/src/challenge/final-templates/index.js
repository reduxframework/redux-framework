/**
 * WordPress dependencies
 */
import ChallengeCongrats from './congrats';
import ChallengeContact from './contact';
import './style.scss'

export default function ChallengeFinalTemplate({finalStatus}) {
	return <ChallengeCongrats />
	// TODO - When feedback is working, uncomment this.
    // if (finalStatus === 'success') return <ChallengeCongrats />
    // return <ChallengeContact />;
}
