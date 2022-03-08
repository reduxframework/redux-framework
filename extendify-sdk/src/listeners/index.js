import { softErrorHandler } from './softerror-encountered'
import { templateHandler } from './template-inserted'

;[templateHandler, softErrorHandler].forEach((listener) => listener.register())
