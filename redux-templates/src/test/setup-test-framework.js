/**
 * External dependencies
 */
import '@babel/polyfill' // Fixes: ReferenceError: regeneratorRuntime is not defined
import '@testing-library/jest-dom/extend-expect';

import React from 'react'

global.React = React

// Some may refer to wp.*, just prevent errors.
global.wp = {}

// Configure Enzyme adapter
import { configure } from 'enzyme'
import Adapter from 'enzyme-adapter-react-16'
configure( { adapter: new Adapter() } )

global.window.requestAnimationFrame = setTimeout
global.window.cancelAnimationFrame = clearTimeout

global.window.matchMedia = () => ( {
	matches: false,
	addListener: () => {},
	removeListener: () => {},
} )

// Setup fake localStorage
const storage = {}
global.window.localStorage = {
	getItem: key => key in storage ? storage[ key ] : null,
	setItem: ( key, value ) => storage[ key ] = value,
}
