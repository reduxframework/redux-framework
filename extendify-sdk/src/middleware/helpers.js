import { Plugins } from '../api/Plugins'

let installedPlugins = []
let activatedPlugins = []

export async function checkIfUserNeedsToInstallPlugins(template) {
    let required = template?.fields?.required_plugins ?? []
    // Hardcoded temporarily to not force EP install
    required = required.filter((p) => p !== 'editorplus')
    if (!required.length) {
        return false
    }

    if (!installedPlugins.length) {
        installedPlugins = Object.keys(await Plugins.getInstalled())
    }
    // if no dependencies are required, then this will be false automatically
    const weNeedInstalls = required.length
        ? required.filter((plugin) => {
              // TODO: if we have better data to work with this can be more literal
              return !installedPlugins.some((k) => {
                  return k.includes(plugin)
              })
          })
        : false

    return weNeedInstalls.length
}

export async function checkIfUserNeedsToActivatePlugins(template) {
    let required = template?.fields?.required_plugins ?? []

    // Hardcoded temporarily to not force EP install
    required = required.filter((p) => p !== 'editorplus')
    if (!required.length) {
        return false
    }

    if (!activatedPlugins.length) {
        activatedPlugins = Object.values(await Plugins.getActivated())
    }

    // if no dependencies are required, then this will be false automatically
    const weNeedActivations = required.length
        ? required.filter((plugin) => {
              // TODO: if we have better data to work with this can be more literal
              return !activatedPlugins.some((k) => {
                  return k.includes(plugin)
              })
          })
        : false

    // if the plugins we need to have activated are not even installed, handle them elsewhere
    if (weNeedActivations) {
        // This call is a bit more expensive so only run it if needed
        if (await checkIfUserNeedsToInstallPlugins(template)) {
            return false
        }
    }
    return weNeedActivations.length
}
