import create from 'zustand'
import { devtools } from 'zustand/middleware'

export const pageState = (name, dataCb) =>
    create(devtools(dataCb, { name: `Extendify Launch ${name}` }))
