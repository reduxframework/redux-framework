const breakpoints = ['tablet', 'desktop']
const utilities = [
    // Be careful with a prefix as it may affect breakpoints (so avoid `$`)
    '-?p[a-z]?-.+',
    '-?m[a-z]?-.+',
    'absolute|relative',
    '(block|inline-block|inline|hidden)$',
    '(flex|inline-flex|items-.+|justify-.+|flex-.+)',
    '(grid|inline-grid|grid-cols-.+|col-.+|row-.+|grid-rows-.+|gap-.+)',
    'order-(1|2)$',
    'rounded(-.{1,2})?-(full|none)',
    'text-(left|center|right)',
    '-?(top-|right-|bottom-|left-)(base|lg)',
    'leading-[a-z]+',
    '(max-)?w-(full|auto)',
    'list-none',
    'border(-.{1})?-0',
]
const withPrefix = (classes) => classes.map(c => `ext-${c}`)
const withBreakpoints = (classes) => classes.map(c => `(${[...breakpoints, ''].join(':|')})${c}`)

module.exports = {
    suggestions: withBreakpoints(withPrefix(utilities)),
}
