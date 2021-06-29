import ShallowRenderer from 'react-test-renderer/shallow';
import renderer from 'react-test-renderer';
import ButtonGroup from '../';

jest.mock('../../preview-import', () => () => 'PreviewImport');
jest.mock('../../dependent-plugins', () => () => 'DependentPlugins');


let realUseContext, useContextMock;
let useEffect;

describe('Button Group', () => {
    it('1. renders correctly: snapshot testing', () => {
        const component = renderer.create(<TestComponent spinner={null} />);
        const tree = component.toJSON();
        expect(tree).toMatchSnapshot();
    });

    describe('2. Testing props with react hook!!!', () => {
        const mockUseEffect = () => {
            useEffect.mockImplementationOnce(f => f());
        };

        beforeEach(() => {
            useEffect = jest.spyOn(React, 'useEffect');
            realUseContext = React.useContext;
            useContextMock = React.useContext = jest.fn();
        });

        afterEach(() => {
            React.useContext = realUseContext;
        });

        it('renders the default classname with spinner null', () => {
            useContextMock.mockReturnValue({spinner: null});
            const element = new ShallowRenderer().render(<ButtonGroup />);
            expect(element.props.className).toBe('redux-templates-import-button-group');
        })

        it('renders disabled status with spinner not null', () => {
            mockUseEffect();
            useContextMock.mockReturnValue({spinner: 1});
            const element = new ShallowRenderer().render(<ButtonGroup />);
            expect(element.props.className).toBe('redux-templates-import-button-group disabled');
        })
    })

});
