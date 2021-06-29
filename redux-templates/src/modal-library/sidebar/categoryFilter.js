const {compose} = wp.compose;
const {withDispatch, withSelect} = wp.data;
const {__} = wp.i18n;

import uniq from 'lodash/uniq';

function CategoryFilter (props) {
    const {categoryData, activeCategory, activePriceFilter, loading, itemType} = props;
    const {setActiveCategory} = props;


    // On the top, All Block, All Template, All Template Kit etc
    const itemTypeLabel = () => {
        if (itemType === 'section') return __('Section', redux_templates.i18n);
        if (itemType === 'page') return __('Template', redux_templates.i18n);
        if (itemType === 'collection') return __('Template Kit', redux_templates.i18n);
    };

    const totalItemCountLabel = () => {
        let totalArr = [], filteredArr = [];
        categoryData.forEach((category) => {
            if (category.hasOwnProperty('filteredData')) filteredArr = [...filteredArr, ...category.filteredData];
            totalArr = [...totalArr, ...category.ids];
        });
        return (activePriceFilter !== '') ?  uniq(filteredArr).length + '/' + uniq(totalArr).length : uniq(totalArr).length;
    };

    const isDisabledCategory = (data) => (data && ((data.hasOwnProperty('filteredData') && data.filteredData.length === 0) || data.ids.length === 0));

    const onChangeCategory = (data) => {
        if (isDisabledCategory(data)) return;
        setActiveCategory(data.slug);
    };
    // Give the selected category(activeCategory) label className as "active"
    const activeClassname = (data) => {
        const categoryLabel = data ? data.slug : '';
        if (isDisabledCategory(data)) return 'disabled';
        return activeCategory === categoryLabel ? 'active' : '';
    };

    return (
        <div>
            <h3>{__('Categories', redux_templates.i18n)}</h3>
            {!loading &&
            <ul className="redux-templates-sidebar-categories">
                {categoryData.length > 0 &&
                <li
                    className={activeClassname(null)}
                    onClick={() => setActiveCategory('')}>
                    {__('All', redux_templates.i18n)} {itemTypeLabel()}s <span>{totalItemCountLabel()}</span>
                </li>
                }

                {categoryData &&
                categoryData.map((data, index) => (
                    <li className={activeClassname(data)} onClick={() => onChangeCategory(data)}
                        key={index}>
                        {data.name}
                        <span> {data.hasOwnProperty('filteredData') && activePriceFilter !== '' ? data.filteredData.length : data.ids.length } </span>
                    </li>
                ))
                }
            </ul>
            }
        </div>
    );
}

export default compose([
    withDispatch((dispatch) => {
        const {setActiveCategory} = dispatch('redux-templates/sectionslist');
        return {
            setActiveCategory
        };
    }),

    withSelect((select, props) => {
        const {getCategoryData, getActiveCategory, getActiveItemType, getLoading} = select('redux-templates/sectionslist');
        return {
            categoryData: getCategoryData(),
            activeCategory: getActiveCategory(),
            itemType: getActiveItemType(),
            loading: getLoading(),
        };
    })
])(CategoryFilter);
