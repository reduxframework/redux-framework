import { hasPluginsActivated } from './hasPluginsActivated'
import { hasRequiredPlugins } from './hasRequiredPlugins'

export const Middleware = (middleware = []) => {
    return {
        hasRequiredPlugins: hasRequiredPlugins,
        hasPluginsActivated: hasPluginsActivated,
        stack: [],
        async check(template) {
            for (const m of middleware) {
                const cb = await this[`${m}`](template)
                this.stack.push(cb.pass ? cb.allow : cb.deny)
            }
        },
        reset() {
            this.stack = []
        },
    }
}

export async function AuthorizationCheck(middleware) {
    const middlewareGenerator = MiddlewareGenerator(middleware.stack)
    while (true) {
        let result
        try {
            result = await middlewareGenerator.next()
        } catch {
            // Reset the stack and exit the middleware
            // This is used if you want to have the user cancel
            middleware.reset()
            throw 'Middleware exited'
        }

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
