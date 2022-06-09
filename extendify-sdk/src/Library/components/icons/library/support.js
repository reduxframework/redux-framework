/**
 * WordPress dependencies
 */
import { SVG, Circle } from '@wordpress/primitives'

const layouts = (
    <SVG
        width="24"
        height="24"
        viewBox="0 0 24 24"
        fill="none"
        xmlns="http://www.w3.org/2000/svg">
        <Circle
            cx="12"
            cy="12"
            r="7.25"
            stroke="currentColor"
            strokeWidth="1.5"
        />
        <Circle
            cx="12"
            cy="12"
            r="4.25"
            stroke="currentColor"
            strokeWidth="1.5"
        />
        <Circle
            cx="11.9999"
            cy="12.2"
            r="6"
            transform="rotate(-45 11.9999 12.2)"
            stroke="currentColor"
            strokeWidth="3"
            strokeDasharray="1.5 4"
        />
    </SVG>
)

export default layouts
