import classNames from 'classnames'
import { createElement } from '@wordpress/element'

export default function Primary({ tagName='button', children, ...props }) {
    props.className = classNames(props.className, 'bg-extendify-main hover:bg-extendify-main-dark py-3 pl-5 pr-3 w-60 rounded no-underline text-base text-white flex justify-center items-center space-x-2')
    return createElement(
        tagName,
        props,
        children,
    )
}
