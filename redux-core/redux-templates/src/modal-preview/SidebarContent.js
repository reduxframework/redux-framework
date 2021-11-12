import {Tooltip, Panel, PanelBody, PanelRow} from '@wordpress/components';
import {more} from '@wordpress/icons';


const {useState, useEffect} = wp.element
const {__} = wp.i18n

import * as Icons from '~redux-templates/icons'
import copy from 'clipboard-copy';
import SafeImageLoad from '~redux-templates/components/safe-image-load';
import {requiresInstall, requiresPro} from '~redux-templates/stores/dependencyHelper'
import React from 'react';

export default function SidebarContent(props) {
	const {itemData, pro} = props;
	const {hash, name, image, blocks, proDependencies, installDependencies, url, source} = itemData;
	const [copied, setCopied] = useState(false);

	const copyHash = () => {
		copy(hash.substring(0, 7));
		setCopied(true);
		setTimeout(function () {
			setCopied(false);
		}, 3500);
	}

	useEffect(() => {
		setCopied(false);
	}, [itemData]);


	if ('redux' === source) {
		const source_instance = redux_templates.supported_plugins['redux-framework'];
	} else {
		const source_instance = redux_templates.supported_plugins[source];
	}

	return (
		<div className="wp-full-overlay-sidebar-content">
			<div className="install-theme-info">
				<h3 className="theme-name">{name} { url && <Tooltip position={'top right'}
																	text={__('Full Preview', redux_templates.i18n)}><a href={url}
																target="_blank"><i
					className="fas fa-external-link-alt"/></a></Tooltip> }</h3>
				<div className="theme-screenshot-wrap">
					<SafeImageLoad url={image} className="theme-screenshot"/>
					{pro ?
						<span className="redux-templates-pro-badge">{__('Premium', redux_templates.i18n)}</span> : ''}
				</div>

				<h5 className="theme-hash">
					<Tooltip position={'top center'} text={__('Copy the template identifier', redux_templates.i18n)}>
						<div className="button-container" onClick={copyHash}>
	                        <span className="button button-secondary the-copy">
		                        <i className="fa fa-copy" aria-hidden="true"></i>
	                        </span>
							<span className="button button-secondary the-hash">{hash.substring(0, 7)}</span>
							{copied && <span className="copied hideMe"><br/>{__('copied', redux_templates.i18n)}</span>}
						</div>
					</Tooltip>
				</h5>
			</div>
			{
				installDependencies && installDependencies.length > 0 &&
				<PanelBody title={__('Required Plugins', redux_templates.i18n)} icon={more} initialOpen={true}>
					<PanelRow className="requirements-list-div">
						<div className="requirements-list">
							<ul>
								{
									installDependencies.map(pluginKey => {
										const pluginInstance = redux_templates.supported_plugins[pluginKey];
										if (!pluginInstance) {
											console.log('Missing plugin details for ' + pluginKey);
											return null;
										}
										const plugin_name = pluginKey.replace('-pro', '').replace('-premium', '').replace(/\W/g, '').toLowerCase();
										if ('redux' === plugin_name) {
											return;
										}
										const IconComponent = Icons[plugin_name];
										return (

											<li key={pluginKey}>
												{IconComponent && <IconComponent/>}
												<span
													className="redux-templates-dependency-name">{pluginInstance.name}</span>
												{requiresInstall(itemData) &&
												<Tooltip position={'bottom center'}
												         text={__('Not Installed', redux_templates.i18n)}>
													<div className='redux-icon-wrapper'><i
														className="fa fa-exclamation-triangle"/></div>
												</Tooltip>
												}
												{pluginInstance.url ?
													<Tooltip position={'top right'}
													         text={__('Visit Plugin Website', redux_templates.i18n)}>
															<span className="pluginURL"><a href={pluginInstance.url}
															                               target="_blank"><i
																className="fas fa-external-link-alt"/></a></span>
													</Tooltip> : null}
											</li>);
									})
								}
							</ul>
						</div>
					</PanelRow>
				</PanelBody>
			}
			{ blocks && blocks.length > 0 &&
			<PanelBody title={__('Blocks Used', redux_templates.i18n)} icon={more} initialOpen={true}>
				<PanelRow className="redux-block-pills">
					<ul>
						{
							blocks.map((block, i) => {
								return (
									<li key={i}><span>{block}</span></li>
								)
							} )
						}
					</ul>
				</PanelRow>
			</PanelBody>
			}

			{
				'redux' !== source &&
				<PanelBody title={__('Template Details', redux_templates.i18n)} icon={more} initialOpen={false}>
					<PanelRow className="redux-block-pills">
						<ul>
							{'redux' !== source && <li><strong>Author</strong>: {source.slice(0,1).toUpperCase() + source.slice(1, source.length)}</li>}
						</ul>
					</PanelRow>
				</PanelBody>
			}
		</div>
	);
}
