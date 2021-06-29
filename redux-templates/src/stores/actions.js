export const actions = {
    setLibrary( library ) {
        return {
            type: 'SET_LIBRARY',
            library
        };
    },
    fetchLibraryFromAPI( path ) {
        return {
            type: 'FETCH_LIBRARY_FROM_API',
            path,
        };
    },
    setActiveItemType( activeItemType ) {
        return {
            type: 'SET_ACTIVE_ITEM_TYPE',
            activeItemType
        }
    },
    setActiveCategory( activeCategory ) {
        return {
            type: 'SET_ACTIVE_CATEGORY',
            activeCategory
        }
    },
    setActiveCollection( activeCollection ) {
        return {
            type: 'SET_ACTIVE_COLLECTION',
            activeCollection
        }
    },
    setActivePriceFilter( activePriceFilter ) {
        return {
            type: 'SET_ACTIVE_PRICE_FILTER',
            activePriceFilter
        }
    },
    setSearchContext( searchContext ) {
        return {
            type: 'SET_SEARCH_CONTEXT',
            searchContext
        }
    },
    setDependencyFilters( dependencyFilters ) {
        return {
            type: 'SET_DEPENDENCY_FILTERS',
            dependencyFilters
        }
    },
    setCurrentPage( currentPage ) {
        return {
            type: 'SET_CURRENT_PAGE',
            currentPage
        }
    },
    setLoading( loading ) {
        return {
            type: 'SET_LOADING',
            loading
        }
    },
    setColumns( columns ) {
        return {
            type: 'SET_COLUMNS',
            columns
        }
    },
    setSortBy( sortBy ) {
        return {
            type: 'SET_SORT_BY',
            sortBy
        }
    },
    appendErrorMessage( errorMessage ) {
        return {
            type: 'APPEND_ERROR_MESSAGE',
            errorMessage: errorMessage || 'Unknown Error'
        }
    },
    discardAllErrorMessages() {
        return {
            type: 'DISCARD_ALL_ERROR_MESSAGES'
        }
    },
    setInstalledDependencies(installedDependencies) {
        return {
            type: 'SET_INSTALLED_DEPENDENCIES',
            installedDependencies
        }
    },
    setTourOpen(isTourOpen) {
        return {
            type: 'SET_TOUR_OPEN',
            isTourOpen
        }
    },
    setTourActiveButtonGroup(data) {
        return {
            type: 'SET_TOUR_ACTIVE_BUTTON_GROUP',
            data
        }
    },
    setTourPreviewVisible(isVisible) {
        return {
            type: 'SET_PREVIEW_VISIBLE',
            isVisible
        }
    },
    setImportingTemplate(importingTemplate) {
        return {
            type: 'SET_IMPORTING_TEMPLATE',
            importingTemplate
        }
    },
    setChallengeStep(data) {
        return {
            type: 'SET_CHALLENGE_STEP',
            data
        }
    },
    setChallengeOpen(data) {
        return {
            type: 'SET_CHALLENGE_OPEN',
            data
        }
    },
    setChallengeTooltipRect(data) {
        return {
            type: 'SET_CHALLENGE_TOOLTIP_RECT',
            data
        }
    },
    setChallengeFinalStatus(data) {
        return {
            type: 'SET_CHALLENGE_FINAL_STATUS',
            data
        }
    },
    setChallengePassed(data) {
        return {
            type: 'SET_CHALLENGE_PASSED',
            data
        }
    },
    setChallengeListExpanded(data){
        return {
            type: 'SET_CHALLENGE_LIST_EXPANDED',
            data
        }
    },
    setActivateDialogDisplay(data) {
        return {
            type: 'SET_ACTIVATE_DIALOG_DISPLAY',
            data
        }
    },
    setImportToAppend(data) {
        return {
            type: 'SET_IMPORT_TO_APPEND',
            data
        }
    },
    setDependencyFilterRule(data) {
        return {
            type: 'SET_DEPENDENCY_FILTER_RULE',
            data
        }
    },
    selectDependencies(data) {
        return {
            type: 'SELECT_DEPENDENCIES',
            data
        }
    },
    clearSearch() {
        return {
            type: 'CLEAR_SEARCH'
        }
    },
	clearState() {
		return {
			type: 'CLEAR_STATE'
		}
	}
};
