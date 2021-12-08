/**
 * Initializes the FontAwesome library.
 */

/**
 * External dependencies
 */
import { config, library } from '@fortawesome/fontawesome-svg-core'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'
import { fas } from '@fortawesome/free-solid-svg-icons'

config.autoAddCss = false
config.autoReplaceSvg = false
config.familyPrefix = 'sbfa'
config.keepOriginalSource = false
config.observeMutations = false
config.showMissingIcons = false

// We need to add all the available icons in the Font Awesome library so we can display them.
library.add( fab, far, fas )
// fab 391
// far 152
// fas 869
