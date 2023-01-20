import { __ } from '@wordpress/i18n'
import { Icon } from '@wordpress/icons'
import classNames from 'classnames'

export const Nav = ({ pages, activePage, setActivePage }) => (
    <nav aria-labelledby="assist-landing-nav">
        <h2 id="assist-landing-nav" className="sr-only">
            {__('Assist navigation', 'extendify')}
        </h2>
        <ul className="flex m-0 p-0 gap-1.5">
            {pages.map((page) => (
                <li className="list-none m-0 p-0" key={page.slug}>
                    <button
                        onClick={() => setActivePage(page.slug)}
                        type="button"
                        aria-current={activePage === page.slug}
                        className={classNames(
                            'w-full pl-4 pr-5 py-4 text-sm text-design-text whitespace-nowrap cursor-pointer flex gap-1.5 items-center focus:outline-none focus:bg-white focus:bg-opacity-20',
                            activePage === page.slug
                                ? 'bg-white bg-opacity-20'
                                : 'bg-transparent hover:bg-white hover:bg-opacity-20',
                        )}>
                        {page.icon && (
                            <span>
                                <Icon
                                    icon={page.icon}
                                    className="fill-current flex"
                                />
                            </span>
                        )}
                        {page.name}
                    </button>
                </li>
            ))}
        </ul>
    </nav>
)
