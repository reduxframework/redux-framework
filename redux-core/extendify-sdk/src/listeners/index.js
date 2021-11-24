import { templateHandler } from './template-inserted'
import { softErrorHandler } from './softerror-encountered'
;[templateHandler, softErrorHandler].forEach((listener) => listener.register())
