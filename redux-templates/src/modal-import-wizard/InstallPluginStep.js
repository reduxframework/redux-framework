import {pluginInfo} from '~redux-templates/stores/dependencyHelper';

const {apiFetch} = wp;
const {compose} = wp.compose;
const {withDispatch} = wp.data;
const {Fragment, useState} = wp.element;
const {__} = wp.i18n;

function InstallPluginStep(props) {

    const {missingPlugins, toNextStep, onCloseWizard} = props;
    const {setInstalledDependencies} = props;
    const [installingPlugin, setInstallingPlugin] = useState(null);
    const [installedList, setInstalledList] = useState([]);
    const [failedList, setFailedList] = useState([]);
    const [waitingList, setWaitingList] = useState(missingPlugins);

    const preInstallInit = () => {
        setInstalledList([]);
        setFailedList([]);
        setWaitingList(missingPlugins);
        setInstallingPlugin(null);
        setInstalledDependencies(false);
    }

    const onInstallPlugins = async () => {
        preInstallInit();
        let localInstalledList = [];
        let localFailedList = [];
        let localWaitingList = [...waitingList];
        for (let pluginKey of missingPlugins) {
            const pluginInstance = redux_templates.supported_plugins[pluginKey];
            localWaitingList = localWaitingList.filter(key => key !== pluginKey)
            setWaitingList(localWaitingList);
            if (!pluginKey || !pluginInstance) {
                setInstallingPlugin(null);
                break;
            }
            setInstallingPlugin({...pluginInstance, pluginKey});
            const reduxProSurfix = (pluginInstance.redux_pro) ? '&redux_pro=1' : '';
            await apiFetch({
                path: 'redux/v1/templates/plugin-install?slug=' + pluginKey + reduxProSurfix,
            }).then(res => {
                    if (res.success) {
                        setInstalledDependencies(true);
                        localInstalledList = [...localInstalledList, pluginKey];
                        setInstalledList(localInstalledList);
                        if (localWaitingList.length === 0) setInstallingPlugin(null);
                    } else {
                        localFailedList = [...localFailedList, pluginKey]
                        setFailedList(localFailedList);
                        if (localWaitingList.length === 0) setInstallingPlugin(null);
                    }
                })
                .catch(res => {
                    localFailedList = [...localFailedList, pluginKey]
                    setFailedList(localFailedList);
                    if (localWaitingList.length === 0) setInstallingPlugin(null);
                });
        }
    }
    if (waitingList.length === 0 && failedList.length === 0 && installingPlugin === null)
        toNextStep();
    return (

        <Fragment>
            <div className="redux-templates-modal-body">
                <h5>{__('Install Required Plugins', redux_templates.i18n)}</h5>
                <p>{__('Plugins needed to import this template are missing. Required plugins will be installed and activated automatically.', redux_templates.i18n)}</p>
                {
                    (installingPlugin === null && failedList.length > 0) &&
                    (<p className='error installError'>
	                    {__('The following plugin(s) failed to install properly. Please manually install them yourself before attempting another import.', redux_templates.i18n)}
                    </p>)
                }

                <ul className="redux-templates-import-progress">
                    {
                        missingPlugins &&
                        missingPlugins.map(pluginKey => {

                            let plugin = pluginInfo(pluginKey)

                            if (installingPlugin && installingPlugin.pluginKey === pluginKey)
                                return (
                                    <li className="installing" key={installingPlugin.pluginKey}>{installingPlugin.name}
                                        <i className="fas fa-spinner fa-pulse"/></li>);
                            if (failedList.includes(pluginKey))
                                return (<li className="failure" key={pluginKey}>{plugin.name} <a href={plugin.url} target="_blank"><i className="fas fa-external-link-alt"/></a></li>);
                            if (waitingList.includes(pluginKey))
                                return (<li className="todo" key={pluginKey}>{plugin.name} {plugin.url &&
                                <a href={plugin.url} target="_blank"><i className="fas fa-external-link-alt"/></a>
                                }</li>);
                            if (installedList.includes(pluginKey))
                                return (<li className="success" key={pluginKey}>{plugin.name} <i
                                    className="fas fa-check-square"/></li>);
                        })
                    }
                </ul>
            </div>
            <div className="redux-templates-modal-footer">
                {waitingList.length !== 0 &&
                <button className="button button-primary" disabled={installingPlugin !== null}
                        onClick={() => onInstallPlugins()}>
                    {installingPlugin !== null && <i className="fas fa-spinner fa-pulse"/>}
                    <span>{__('Install', redux_templates.i18n)}</span>
                </button>
                }
                <button className="button button-secondary" disabled={installingPlugin !== null}
                        onClick={onCloseWizard}>
                    {__('Cancel', redux_templates.i18n)}
                </button>
            </div>
        </Fragment>
    );
}


export default compose([
    withDispatch((dispatch) => {
        const {
            setInstalledDependencies
        } = dispatch('redux-templates/sectionslist');
        return {
            setInstalledDependencies
        };
    })
])(InstallPluginStep);
