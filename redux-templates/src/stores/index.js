const {apiFetch} = wp;
const {registerStore} = wp.data;

import {initialState, reducer} from './reducer';
import {actions} from './actions';
import cloneDeep from 'lodash/cloneDeep';
import sortBy from 'lodash/sortBy';
import countBy from 'lodash/countBy';
import map from 'lodash/map';
import flattenDeep from 'lodash/flattenDeep';
import uniq from 'lodash/uniq';
import uniqBy from 'lodash/uniqBy';
import {applyCategoryFilter, applySearchFilter, applyHashFilter, applyPriceFilter, applyDependencyFilters, valueOfDependencyFilter, flattenPageData} from './filters'
import {getCurrentState, getCollectionChildrenData, loadChallengeStep, NONE_KEY} from './helper';
import {isTemplatePremium} from './dependencyHelper'
import {installedBlocksTypes} from './actionHelper';

const getOriginalPageData = (state) => {
    if (state.activeItemType === 'collection' && state.collection.activeCollection !== null)
        return getCollectionChildrenData(state.library, state.collection.activeCollection);
    return getCurrentState(state).data;
};

const getActivePriceFilter = (state) => {
    return getCurrentState(state).priceFilter;
};
const getSearchContext = (state) => {
    return (state.activeItemType !== 'saved') ? getCurrentState(state).searchContext : null;
};

const getActiveCategory = (state) => {
    return state[state.activeItemType].activeCategory;
};

const getCurrentPage = (state) => {
    return state[state.activeItemType].currentPage;
};
const getActiveItemType = (state) => {
    return state.activeItemType;
};

// get relevant page data, apply category, price, search, dependent filters
const getPageData = (state, applyDependencyFilter = true) => {
    let pageData = getOriginalPageData(state);
    const searchKeyword = getSearchContext(state);
    let hashFilteredData = [];
    // Hash filter to take priority
    if (state.activeItemType !== 'collection' && searchKeyword.length > 5) hashFilteredData = applyHashFilter(pageData, searchKeyword);
    // Full search for pageData
    if (pageData && Object.keys(pageData).length > 0) {
        pageData = applySearchFilter(pageData, searchKeyword);
        if (applyDependencyFilter) pageData = applyDependencyFilters(pageData, getDependencyFilters(state), getDependencyFilterRule(state));

        pageData = applyPriceFilter(pageData, getActivePriceFilter(state), getDependencyFilters(state));
        if (state.collection.activeCollection === null || state.activeItemType !== 'collection') {
            pageData = applyCategoryFilter(pageData, getActiveCategory(state));
            pageData = sortBy(pageData, getCurrentState(state).sortBy);
        }
        return uniqBy([...pageData, ...hashFilteredData], 'ID');
    }
    return null;
};

const getDependencyFilters = (state) => {
    return {...getAllDependencFilters(state), ...getCurrentState(state).dependencyFilters};
};

const getAllDependencFilters = (state) => {
    const activeState =  state[state.activeItemType || 'section'];
    return [...activeState.wholePlugins, ...activeState.thirdPartyPlugins].reduce((acc, cur) => {
        return {...acc, [cur]: {value: false} };
    }, undefined)
};


const getDependencyFiltersStatistics = (state) => {
    const pageData = flattenPageData(getOriginalPageData(state));
    const dependentPluginsArray = uniq(flattenDeep(map(pageData, 'dependencies')));
    let dependencyFilters = getDependencyFilters(state);
    Object.keys(dependencyFilters)
        .forEach((plugin) => {
            dependencyFilters[plugin] = {...dependencyFilters[plugin], disabled: dependentPluginsArray.indexOf(plugin) === -1}
        })
    dependencyFilters[NONE_KEY] = {value: valueOfDependencyFilter(dependencyFilters[NONE_KEY]), disabled: false};
    return dependencyFilters;
};
const getDependencyFilterRule = (state) => {
    return state[state.activeItemType].dependencyFilterRule;
};
registerStore('redux-templates/sectionslist', {

    reducer,
    actions,

    selectors: {
        fetchLibraryFromAPI(state) {
            return state.library;
        },
        receive(state) {
            return state.sections;
        },

        getActivePriceFilter,
        getSearchContext,
        getDependencyFilters,
        getDependencyFiltersStatistics,
        getActiveItemType,
        getCurrentPage,
        getActiveCategory,
        getDependencyFilterRule,
        getWholePlugins(state) {
            return (state.activeItemType !== 'saved') ? getCurrentState(state).wholePlugins : null;
        },
        getThirdPartyPlugins(state) {
            return (state.activeItemType !== 'saved') ? getCurrentState(state).thirdPartyPlugins : null;
        },
        // get categories from currentState, sortBy alphabetically, with the count of pageData within the current category
        getCategoryData(state) {
            let categories = [];
            let pageData = getOriginalPageData(state);
            if (pageData && Object.keys(pageData).length > 0) {
                pageData = applySearchFilter(pageData, getSearchContext(state));
                pageData = applyDependencyFilters(pageData, getDependencyFilters(state), getDependencyFilterRule(state));
                pageData = applyPriceFilter(pageData, getActivePriceFilter(state), getDependencyFilters(state));
            }
            if (state.collection.activeCollection === null || state.activeItemType !== 'collection') {
                categories = cloneDeep(getCurrentState(state).categories);
                categories = categories.map(category => {
                    const filteredData = map(pageData[category.slug], 'id');
                    return {...category, filteredData};
                });
            }

            categories = sortBy(categories, 'name');
            return categories;
        },
        // get relevant page data, apply category, price, search, dependent filters
        getPageData,

        getStatistics(state) {
            let pageData = getOriginalPageData(state);
            let staticsData = {true: 0, false: 0};
            if (pageData && Object.keys(pageData).length > 0) {
                pageData = applySearchFilter(pageData, getSearchContext(state));
                pageData = applyDependencyFilters(pageData, getDependencyFilters(state), getDependencyFilterRule(state));
                if (state.collection.activeCollection === null || state.activeItemType !== 'collection') pageData = applyCategoryFilter(pageData, getActiveCategory(state));
                staticsData = countBy(pageData, (item) => isTemplatePremium(item, getDependencyFilters(state)) === true);
            }
            return staticsData;
        },
        getLoading(state) {
            return state.loading;
        },
        getColumns(state) {
            return state.columns;
        },
        getSortBy(state) {
            return getCurrentState(state).sortBy;
        },
        getActiveCollection(state) {
            return state.collection.activeCollection;
        },
        getActiveCollectionData(state) {
            if (state.library && state.library.collections && state.collection)
                return state.library.collections[state.collection.activeCollection];
            return null;
        },
        getSaved(state) {
            return state.saved;
        },
        getErrorMessages(state) {
            return state.errorMessages;
        },
        getInstalledDependencies(state) {
            return state.installedDependencies;
        },
        getTourOpen(state) {
            return state.tour.isOpen;
        },
        getTourActiveButtonGroup(state) {
            return state.tour.activeButtonGroup;
        },
        getTourPreviewVisible(state) {
            return state.tour.isPreviewVisible;
        },
        getImportingTemplate(state) {
            return state.importingTemplate;
        },
        getChallengeStep(state) {
            return loadChallengeStep();
        },
        getChallengeOpen(state) {
            return state.challenge.isOpen;
        },
        getChallengeTooltipRect(state) {
            return state.challenge.tooltipRect;
        },
        getChallengeFinalStatus(state) {
            return state.challenge.finalStatus;
        },
        getChallengePassed(state) {
            return state.challenge.passed;
        },
        getChallengeListExpanded(state) {
            return state.challenge.listExpanded;
        },
        getActivateDialogDisplay(state) {
            return state.activateDialog;
        },
        getImportToAppend(state) {
            return state.isImportToAppend;
        }
    },

    controls: {
        FETCH_LIBRARY_FROM_API(action) {
            return apiFetch({path: action.path, method: 'POST', data: {registered_blocks: installedBlocksTypes()}});
        },
        FETCH_SAVED_FROM_API(action) {
            return apiFetch({path: action.path, method: 'POST', data: {registered_blocks: installedBlocksTypes()}});
        }
    },

    resolvers: {
        * fetchLibraryFromAPI(state) {
            try {
                const receiveSectionResult = yield actions.fetchLibraryFromAPI('redux/v1/templates/library');
                return actions.setLibrary(receiveSectionResult.data);
            } catch (error) {
                return actions.appendErrorMessage(error.code + ' ' + error.message)
            }
        }
    },

    initialState
});
