import classNames from 'classnames'
import { createElement } from '@wordpress/element'

export default function Primary({ tagName = 'button', children, ...props }) {
    props.className = classNames(
        props.className,
        'bg-extendify-main hover:bg-extendify-main-dark cursor-pointer rounded no-underline text-base text-white flex justify-center items-center',
    )
    return createElement(tagName, props, children)
}
