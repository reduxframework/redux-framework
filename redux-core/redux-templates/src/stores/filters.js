import {isTemplatePremium} from './dependencyHelper';
import {missingPluginsArray, NONE_KEY} from './helper';
const REDUXTEMPLATES_PRO_KEY = 'redux-pro';
// Just get current Page Data
export const applyCategoryFilter = (pageData, activeCategory) => {
    let currentPageData = [];
    let tempDataID = [];
    if (activeCategory && pageData[activeCategory] && Array.isArray(pageData[activeCategory]) && pageData[activeCategory].length > 0) {
        pageData[activeCategory].map(value => {
            if (!(tempDataID.indexOf(value.ID) > -1)) {
                currentPageData.push(value);
                tempDataID.push(value.ID);
            }
        });
    } else
        for (let key in pageData) {
            Array.isArray(pageData[key]) && pageData[key].map(value => {
                if (!(tempDataID.indexOf(value.ID) > -1)) {
                    currentPageData.push(value);
                    tempDataID.push(value.ID);
                }
                else {
                    if (value.parentID && !(tempDataID.indexOf(value.ID) > -1)) {
                        currentPageData.push(value);
                        tempDataID.push(value.ID);
                    }
                }
            })
        }
    return currentPageData;
};

export const applySearchFilter = (pageData, searchContext) => {
    let lowercasedSearchContext = searchContext.toLowerCase();
    if (Array.isArray(pageData)) {
        return pageData.filter(item => (item.name.toLowerCase().indexOf(lowercasedSearchContext) !== -1))
    } else {
        let newPageData = {};
        Object.keys(pageData).forEach(key => {
            newPageData[key] =  pageData[key].filter(item => (item.name.toLowerCase().indexOf(lowercasedSearchContext) != -1))
        });
        return newPageData;
    }
}



export const applyHashFilter = (pageData, searchContext) => {
    let lowercasedSearchContext = searchContext.toLowerCase();
    if (Array.isArray(pageData)) {
        return pageData.filter(item => (item.hash && item.hash.toLowerCase().indexOf(lowercasedSearchContext) !== -1))
    } else {
        let newPageData = [];
        Object.keys(pageData).forEach(key => {
            let filteredData = pageData[key].filter(item => (item.hash && item.hash.toLowerCase().indexOf(lowercasedSearchContext) !== -1));
            newPageData = [...newPageData, ...filteredData];
        });
        return newPageData;
    }
}

// Apply Price filter afterwards : Should make sure if it is a best practise to split this filtering
export const applyPriceFilter = (pageData, activePriceFilter, activeDependencyFilter) => {
    if (activePriceFilter !== '') {
        if (Array.isArray(pageData)) {
            return pageData.filter(item => {
                if (activePriceFilter === 'free') return (isTemplatePremium(item, activeDependencyFilter) === false);
                if (activePriceFilter === 'pro') return  isTemplatePremium(item, activeDependencyFilter);
            });
        } else {
            let newPageData = {};
            Object.keys(pageData).forEach(key => {
                newPageData[key] =  pageData[key].filter(item => {
                    if (activePriceFilter === 'free') return (isTemplatePremium(item, activeDependencyFilter) === false);
                    if (activePriceFilter === 'pro') return isTemplatePremium(item, activeDependencyFilter);
                });
            });
            return newPageData;
        }
    }
    return pageData;
}


export const applyDependencyFilters = (pageData, dependencyFilters, dependencyFilterRule) => {
    const truthyDependenciesList = truthyDependencyFiltersList(dependencyFilters);
    if (Array.isArray(pageData)) {
        return pageData.filter(item => isTemplateDependencyFilterIncluded(item, truthyDependenciesList, dependencyFilterRule));
    } else {
        let newPageData = {};
        Object.keys(pageData).forEach(key => {
            newPageData[key] =  pageData[key].filter(item => isTemplateDependencyFilterIncluded(item, truthyDependenciesList, dependencyFilterRule));
        });
        return newPageData;
    }
}

const isTemplateDependencyFilterIncluded = (item, truthyDependenciesList, dependencyFilterRule) => {
    // console.log("now", item.dependencies, dependencyFilters);
    // No dependencies at all case
    if (!item.dependencies || Object.keys(item.dependencies).length === 0) return truthyDependenciesList.includes(NONE_KEY);

    // Normal dependencies filter check
    const filteredList = item.dependencies.filter((dependency) => truthyDependenciesList.includes(dependency));

    return dependencyFilterRule ? item.dependencies.length === filteredList.length : filteredList.length > 0; // filter rule = ture => AND operation
}

// check dependency filter is selected on sidebar
// Input: dependencyFilter={'qubely',
export const valueOfDependencyFilter = (dependencyFilter) => {
    if (dependencyFilter != null && dependencyFilter.hasOwnProperty('value')) return (dependencyFilter.value === true);
    return (dependencyFilter === true);
}

const truthyDependencyFiltersList = (dependencyFilters) => {
    return Object.keys(dependencyFilters).filter((key) => dependencyFilters[key].value === true);
}

export const flattenPageData = (pageData) => {
    const currentPageData = [];
    if (Array.isArray(pageData) === false) {
        for (let key in pageData) {
            Array.isArray(pageData[key]) && pageData[key].map(value => {
                currentPageData.push(value);
            })
        }
        return currentPageData;
    }
    return pageData;
};
