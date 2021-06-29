import { shallow, mount } from 'enzyme';
import {TabHeader} from '../';

describe('Tab Header', () => {
    // 1. Snapshot testing
    it('renders correctly', () => {
        const setSearchContext = jest.fn();
        const setActiveItemType = jest.fn();
        const wrapper = shallow(<TabHeader setSearchContext={setSearchContext} setActiveItemType={setActiveItemType} />);
        expect(wrapper).toMatchSnapshot();
    });


});
