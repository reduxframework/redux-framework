const {compose} = wp.compose;
const {withDispatch, withSelect} = wp.data;
const {__} = wp.i18n;

function PriceFilter (props) {
	const {setActivePriceFilter, activePriceFilter, statistics} = props;

    const getClassnames = (priceFilter) => {
        let classNames = [];
        classNames.push((priceFilter === activePriceFilter) ? 'active' : '');
        classNames.push(noStatistics(priceFilter) ? 'disabled' : '');
        return classNames.join(' ');
    }

    const noStatistics = (priceFilter) => {
        if (priceFilter === '') return false;
        if (priceFilter === 'free')
            return (!statistics['false'] || statistics['false'] < 1);
        else
            return (!statistics['true'] || statistics['true'] < 1);
    }

    return (
        <div className='redux-templates-template-filter-button-group'>
                <button onClick={() => setActivePriceFilter('')}
                        className={getClassnames('')}>{__('All', redux_templates.i18n)}</button>
                <button onClick={() => setActivePriceFilter('free')} className={getClassnames('free')}
                        disabled={noStatistics('free')}>{__('Free', redux_templates.i18n)}</button>
                <button onClick={() => setActivePriceFilter('pro')} className={getClassnames('pro')}
                        disabled={noStatistics('pro')}>
                    <img src={redux_templates.plugin + 'assets/img/icon-premium.svg'} alt=""/>
                    {__('Premium', redux_templates.i18n)}
                </button>
        </div>
    );
}


export default compose([
    withDispatch((dispatch) => {
        const {setActivePriceFilter} = dispatch('redux-templates/sectionslist');
        return {
            setActivePriceFilter
        };
    }),

    withSelect((select, props) => {
        const {getStatistics, getActivePriceFilter} = select('redux-templates/sectionslist');
        return {
            activePriceFilter: getActivePriceFilter(),
            statistics: getStatistics()
        };
    })
])(PriceFilter);
