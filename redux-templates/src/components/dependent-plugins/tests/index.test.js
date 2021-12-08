import renderer from 'react-test-renderer';
import {mount, shallow} from 'enzyme';
import DependentPlugins from '..';
import {SingleItemProvider} from '../../../contexts/SingleItemContext';

window.redux-templates = {
    supported_plugins: {
        ugb: {
            name: 'Stackable',
            url: 'https://wpstackable.com/premium/#pricing-table',
            has_pro: true,
            slug: 'stackable-ultimate-gutenberg-blocks',
            premium_slug: 'stackable-ultimate-gutenberg-blocks-premium'
        },
        qubely: {
            name: 'Qubely',
            url: 'https://www.themeum.com/qubely-pricing/',
            has_pro: true,
            premium_slug: 'qubely-pro'
        }
    }
}

const singleMock = {
    data: {ID: 1, blocks: {}},
    showDependencyBlock: true
};

const WrappedDependentPlugins = (props) => {
    const {singleValue} = props;
    return (
        <SingleItemProvider value={{...singleMock, ...singleValue}}>
            <DependentPlugins />
        </SingleItemProvider>
    );
}

describe('Dependent Plugins part within Button Group component', () => {
    it('1. renders correctly: snapshot testing', () => {
        const component = renderer.create(
            <WrappedDependentPlugins />
        );
        const tree = component.toJSON();
        expect(tree).toMatchSnapshot();
    });

    describe('2. Testing props', () => {
        it('renders nothing when showDependencyBlock of SingleItemProvider is false', () => {
            const component = shallow(
                <WrappedDependentPlugins singleValue={{showDependencyBlock: false}} />
            );
            expect(component.html()).toBeFalsy();
        });

        it('renders just wrapper .redux-templates-button-display-dependencies when no blocks data is given', () => {
            const component = mount(
                <WrappedDependentPlugins />
            );
            expect(component.find('.redux-templates-button-display-dependencies').text()).toBe('');
        });


        it('renders blocks dependency plugins when dependency plugins data are provided', () => {
            const component = mount(
                <WrappedDependentPlugins singleValue={{data: {blocks: {qubely: [], ugb: []}}}} />
            );
            expect(component.find('.redux-templates-button-display-dependencies').children()).toHaveLength(2);
        });
    });

});
