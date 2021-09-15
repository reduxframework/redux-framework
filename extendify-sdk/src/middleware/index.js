import { hasRequiredPlugins } from './hasRequiredPlugins'
import { hasPluginsActivated } from './hasPluginsActivated'
import { check as checkNeedsRegistrationModal } from './NeedsRegistrationModal'

export const Middleware = (middleware = []) => {
    return {
        hasRequiredPlugins: hasRequiredPlugins,
        hasPluginsActivated: hasPluginsActivated,
        NeedsRegistrationModal: checkNeedsRegistrationModal,
        stack: [],
        async check(template) {
            for (const m of middleware) {
                const cb = await this[`${m}`](template)
                setTimeout(() => {
                    this.stack.push(cb.pass
                        ? cb.allow
                        : cb.deny)
                }, 0)
            }
        },
        reset() {
            this.stack = []
        },
    }
}

export async function AuthorizationCheck(pipes) {
    const middleware = MiddlewareGenerator(pipes)
    while (true) {
        const result = await middleware.next()

        // TODO: Could probably have a check for errors here
        if (result.done) {
            break
        }
    }
}
export async function* MiddlewareGenerator(middleware) {
    for (const m of middleware) {
        yield await m()
    }
}
