import renderer from 'react-test-renderer';
import {mount} from 'enzyme';
import PreviewImport from '..';
import {SingleItemProvider} from '../../../contexts/SingleItemContext';

let templateMock = {
    openSitePreviewModal: jest.fn(),
    onImportTemplate: jest.fn(),
    spinner: null
};
let singleMock = {
    data: {ID: 1, url: 'url'},
    index: 0,
    pageData: null
};
let useEffect;
const WrappedPreviewImport = (props) => {
    const {templateValue, singleValue} = props;
    return (
        <SingleItemProvider value={{...singleMock, ...singleValue}}>
            <PreviewImport />
        </SingleItemProvider>
    );
}


describe('Preview Import buttons', () => {
    it('1. renders correctly: snapshot testing', () => {
        const component = renderer.create(
            <WrappedPreviewImport />
        );
        const tree = component.toJSON();
        expect(tree).toMatchSnapshot();
    });

    describe('2. Testing props', () => {
        it('does not display preview button when data.url is not set', () => {
            const testRenderer = renderer.create(
                <WrappedPreviewImport singleValue={{data: {url: null}}} />
            );
            const testInstance = testRenderer.root;
            expect(testInstance.findAllByProps({className: 'redux-templates-button preview-button'}).length).toBe(0);
        });

        it('displays preview button when data.url is set', () => {
            const testRenderer = renderer.create(
                <WrappedPreviewImport singleValue={{data: {url: 'url'}}} />
            );
            const testInstance = testRenderer.root;
            expect(testInstance.findAllByProps({className: 'redux-templates-button preview-button'}).length).toBe(1);
        });

        it('displays import button with download icon with default context value', () => {
            const testRenderer = renderer.create(
                <WrappedPreviewImport />
            );
            const testInstance = testRenderer.root;
            expect(testInstance.findAllByProps({className: 'fas fa-download'}).length).toBeGreaterThan(0);
        });

        it('displays import button with download icon with spinner null', () => {
            const testRenderer = renderer.create(
                <WrappedPreviewImport templateValue={{spinner: null}} />
            );
            const testInstance = testRenderer.root;
            expect(testInstance.findAllByProps({className: 'fas fa-download'}).length).toBeGreaterThan(0);
        });

        it('displays import button with spinner icon when spinner and data.ID match', () => {
            const testRenderer = renderer.create(
                <WrappedPreviewImport templateValue={{spinner: 1}} singleValue={{data: {ID: 1}}} />
            );
            const testInstance = testRenderer.root;
            expect(testInstance.findAllByProps({className: 'fas fa-spinner fa-pulse'}).length).toBeGreaterThan(0);
        })
    });

    describe('4. Testing Events', () => {
        const mockUseEffect = () => {
            useEffect.mockImplementationOnce(f => f());
        };

        beforeEach(() => {
            useEffect = jest.spyOn(React, 'useEffect');
        });

        it('click preview to call openSitePreviewModal of template modal context', () => {
            const component = mount(<WrappedPreviewImport />);
            const previewButton = component.find('.preview-button');
            previewButton.simulate('click');
            expect(templateMock.openSitePreviewModal).toHaveBeenCalled();
        });

        it('click preview to call openSitePreviewModal of template modal context with right parameter', () => {
            const component = mount(<WrappedPreviewImport singleValue={{index: 1, pageData: 'pagedata'}} />);
            const previewButton = component.find('.preview-button');
            previewButton.simulate('click');
            expect(templateMock.openSitePreviewModal).toHaveBeenCalledWith(1,'pagedata');
        });

        it('click download not to call onImportTemplate when other operation is going on(spinner is not null)', () => {
            const component = mount(<WrappedPreviewImport templateValue={{spinner: 1}} />);
            const downloadButton = component.find('.download-button');
            downloadButton.simulate('click');
            expect(templateMock.onImportTemplate).not.toHaveBeenCalled();
        });

        it('click download to call onImportTemplate of template modal context', () => {
            const component = mount(<WrappedPreviewImport />);
            const downloadButton = component.find('.download-button');
            downloadButton.simulate('click');
            expect(templateMock.onImportTemplate).toHaveBeenCalled();
        });

        it('click download to call onImportTemplate of template modal context with right parameter', () => {
            const component = mount(<WrappedPreviewImport singleValue={{data: {ID: 1}}} />);
            const downloadButton = component.find('.download-button');
            downloadButton.simulate('click');
            expect(templateMock.onImportTemplate).toHaveBeenCalledWith({ID: 1});
        });

    });


});
