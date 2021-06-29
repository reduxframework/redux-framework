import {shallow, mount} from 'enzyme';
import {ErrorNotice} from '../';

describe('Error Notice', () => {
    // 1. Snapshot testing
    it('renders correctly', () => {
        const onButtonClickMock = jest.fn();
        const wrapper = shallow(<ErrorNotice errorMessages={['Some Error Message']} discardAllErrorMessages={onButtonClickMock} />);
        expect(wrapper).toMatchSnapshot();
    });

    it('has always one p tag', () => {
        const props = {
                errorMessages: []
            },
            ErrorNoticeComponent = mount(<ErrorNotice {...props} />);
        expect(ErrorNoticeComponent.find('p').length).toBe(1);
    });

    // 2. props check: proper rendering of error message
    it('should show the message properly within p tag', () => {
        const props = {
                errorMessages: ['Test Error']
            },
            ErrorNoticeComponent = mount(<ErrorNotice {...props} />);
        expect(ErrorNoticeComponent.find('p').text()).toBe('An error occurred:Test Error');
    });

    // 3. prop data type testing
    it('check the type of props', () => {
        const props = {
                errorMessages: [],
                discardAllErrorMessages: jest.fn()
            },
            ErrorNoticeComponent = mount(<ErrorNotice {...props} />);
        expect(Array.isArray(ErrorNoticeComponent.prop('errorMessages'))).toBe(true);
        expect(typeof ErrorNoticeComponent.prop('discardAllErrorMessages')).toBe('function');
    });

    // 4. Event
    it('click close to call discardAllErrorMessages', () => {
        const props = {
                errorMessages: [],
                discardAllErrorMessages: jest.fn()
            },
            ErrorNoticeComponent = mount(<ErrorNotice {...props} />).find('button');
        ErrorNoticeComponent.simulate('click');
        expect(props.discardAllErrorMessages).toHaveBeenCalled();

    })


});
