import {
    useEffect, useState, useRef,memo,
} from '@wordpress/element'
import { AuthorizationCheck, Middleware } from '../middleware'
import { injectTemplateBlocks } from '../util/templateInjection'
import { useWantedTemplateStore } from '../state/Importing'
import { useUserStore } from '../state/User'
import { useGlobalStore } from '../state/GlobalState'
import { __, sprintf } from '@wordpress/i18n'
import { Templates as TemplatesApi } from '../api/Templates'
import { useInView } from 'react-intersection-observer'
import { BlockPreview } from '@wordpress/block-editor'
import { rawHandler } from '@wordpress/blocks'
import { render } from '@wordpress/element'
import ExtendifyLibrary from '../ExtendifyLibrary'
import Primary from './buttons/Primary'
import SplitModal from './modals/SplitModal'

const LiveBlocksMemoized = memo(({ blocks }) => {
    return <div className='with-light-shadow relative'>
        <BlockPreview
            blocks={blocks}
            live={false}
            viewportWidth={1400} />
    </div>
}, (oldBlocks, newBlocks) => oldBlocks.clientId == newBlocks.clientId)
const canImportMiddleware = Middleware(['NeedsRegistrationModal', 'hasRequiredPlugins', 'hasPluginsActivated'])

export function ImportTemplateBlock({ template }) {
    const importButtonRef = useRef(null)
    const once = useRef(false)
    const canImport = useUserStore(state => state.canImport)
    const setWanted = useWantedTemplateStore(state => state.setWanted)
    const setOpen = useGlobalStore(state => state.setOpen)
    const blocks = rawHandler({ HTML: template.fields.code })
    const [hasBeenSeen, setHasBeenSeen] = useState(false)
    const [onlyLoadInView, inView] = useInView()
    const [showModal, setShowModal] = useState(false)

    const focusTrapInnerBlocks = () => {
        if (once.current) return
        once.current = true
        Array.from(importButtonRef.current.querySelectorAll('a, button, input, textarea, select, details, [tabindex]:not([tabindex="-1"])'))
            .forEach(el => el.setAttribute('tabIndex', '-1'))
    }

    const importTemplates = async () => {
        await canImportMiddleware.check(template)
        AuthorizationCheck(canImportMiddleware.stack).then(() => {
            setTimeout(() => {
                injectTemplateBlocks(blocks, template)
                    .then(() => setOpen(false))
                    .then(() => render(<ExtendifyLibrary/>, document.getElementById('extendify-root')))
                    .then(()=> canImportMiddleware.reset())
            }, 100)
        })
    }

    const handleKeyDown = (event) => {
        if (['Enter', 'Space', ' '].includes(event.key)) {
            event.stopPropagation()
            event.preventDefault()
            importTemplate()
        }
    }

    const importTemplate = () => {
        if(!canImport()) {
            setShowModal(true)
            return
        }
        TemplatesApi.maybeImport(template)
        // TODO: Is this needed? Check when removing all single page views
        setWanted(template)
        importTemplates()
    }

    useEffect(() => {
        if (!hasBeenSeen && inView) {
            setHasBeenSeen(true)
        }
    }, [inView, hasBeenSeen, template])

    return <>
        <div
            role="button"
            tabIndex="0"
            ref={importButtonRef}
            aria-label={sprintf(__('Press to import %s', 'extendify-sdk'), template?.fields?.type)}
            className="mb-8 cursor-pointer button-focus"
            onFocus={focusTrapInnerBlocks}
            onClick={importTemplate}
            onKeyDown={handleKeyDown}>
            <div ref={onlyLoadInView} className="invisible absolute inset-0 pointer-events-none"></div>
            {hasBeenSeen && <LiveBlocksMemoized blocks={blocks} />}
        </div>
        { showModal && <SplitModal
            isOpen={showModal}
            onRequestClose={()=>setShowModal(false)}
            left={<>
                <div className="flex space-x-2 items-center justify-center mb-10">
                    <svg fill="none" height="30" viewBox="0 0 153 30" width="153" xmlns="http://www.w3.org/2000/svg"><path d="m33.2598 24.7079v-18.89872h12.7345v3.29434h-8.7388v4.50318h8.0835v3.2944h-8.0835v4.5124h8.7756v3.2944zm19.1224-14.174 2.6023 4.9553 2.6668-4.9553h4.0327l-4.1066 7.087 4.2173 7.087h-4.0141l-2.7961-4.9-2.7499 4.9h-4.0603l4.2079-7.087-4.0602-7.087zm19.1756 0v2.9529h-8.5359v-2.9529zm-6.598-3.39592h3.9312v13.21432c0 .363.0552.646.1661.849.1106.1968.2644.3353.4612.4152.2031.08.437.12.7014.12.1845 0 .3692-.0154.5537-.0461.1845-.0369.3261-.0646.4244-.0831l.6183 2.9252c-.1968.0615-.4736.1323-.8305.2122-.3568.0862-.7906.1386-1.301.157-.9474.0368-1.7781-.0892-2.4916-.3783-.7074-.2893-1.2581-.7383-1.6518-1.3474-.3937-.609-.5875-1.378-.5814-2.3069zm15.466 17.84662c-1.458 0-2.7131-.2951-3.7651-.8857-1.0457-.5968-1.8517-1.4396-2.4175-2.5285-.5661-1.0951-.8491-2.39-.8491-3.8849 0-1.458.283-2.7376.8491-3.8388.5658-1.1012 1.3625-1.9594 2.39-2.5746 1.0334-.6152 2.2454-.9228 3.6356-.9228.9353 0 1.8056.1507 2.6116.4521.812.2954 1.5195.7414 2.1224 1.3381.6091.5967 1.0827 1.3473 1.4212 2.2516.3382.8982.5073 1.9502.5073 3.1559v1.0797h-11.9683v-2.4362h8.268c0-.5659-.123-1.0673-.369-1.5041-.2462-.4368-.5875-.7782-1.0243-1.0243-.4307-.2522-.932-.3783-1.5041-.3783-.5968 0-1.1259.1384-1.5873.4152-.4552.2707-.8121.6367-1.0704 1.0981-.2584.4553-.3907.9628-.3967 1.5226v2.3162c0 .7014.129 1.3073.3874 1.8179.2646.5106.6368.9043 1.1167 1.1812.4797.2768 1.0487.4153 1.707.4153.4368 0 .8368-.0616 1.1997-.1846.363-.1231.6737-.3076.932-.5537.2584-.2461.4552-.5475.5906-.9043l3.6358.2399c-.1845.8736-.563 1.6364-1.1351 2.2885-.5659.646-1.298 1.1505-2.1963 1.5134-.8919.3569-1.9223.5351-3.0912.5351zm13.002-8.4711v8.1944h-3.931v-14.174h3.7465v2.5007h.1661c.3137-.8244.8397-1.4764 1.5779-1.9563.7383-.486 1.6335-.729 2.6855-.729.9842 0 1.8423.2153 2.5742.6459.732.4307 1.301 1.0459 1.707 1.8456.406.7936.609 1.741.609 2.8422v9.0249h-3.9305v-8.3236c.0061-.8674-.2155-1.5441-.6646-2.0301-.4491-.4922-1.0674-.7382-1.8547-.7382-.529 0-.9966.1138-1.4026.3414-.4.2276-.7137.5598-.9413.9966-.2216.4306-.3352.9505-.3415 1.5595zm17.4572 8.425c-1.077 0-2.052-.2767-2.926-.8305-.867-.5598-1.556-1.381-2.067-2.4638-.504-1.0889-.756-2.4238-.756-4.0049 0-1.6241.261-2.9744.784-4.051.523-1.0828 1.218-1.8917 2.086-2.427.873-.5413 1.83-.812 2.869-.812.794 0 1.455.1353 1.984.406.536.2646.966.5968 1.292.9966.333.3937.585.7813.757 1.1627h.12v-7.10542h3.922v18.89872h-3.876v-2.2701h-.166c-.185.3937-.446.7844-.784 1.172-.333.3814-.766.6981-1.301.9504-.53.2523-1.176.3783-1.938.3783zm1.246-3.1282c.633 0 1.168-.1722 1.605-.5167.443-.3507.781-.8398 1.015-1.4673.24-.6275.36-1.3626.36-2.2054s-.117-1.5749-.351-2.1963c-.233-.6213-.572-1.1012-1.015-1.4395-.442-.3384-.981-.5076-1.614-.5076-.646 0-1.191.1754-1.634.526-.443.3507-.778.8367-1.006 1.458-.227.6214-.341 1.3412-.341 2.1594 0 .8243.114 1.5533.341 2.187.234.6275.569 1.1196 1.006 1.4764.443.3507.988.526 1.634.526zm10.051 2.8976v-14.174h3.931v14.174zm1.984-16.00116c-.584 0-1.086-.19379-1.504-.58137-.418-.39372-.628-.86435-.628-1.41187 0-.54137.21-1.00584.628-1.39339.418-.39373.916-.59059 1.495-.59059.59 0 1.092.19686 1.504.59059.418.38755.627.85202.627 1.39339 0 .54752-.209 1.01815-.627 1.41187-.412.38758-.911.58137-1.495.58137zm12.718 1.82716v2.9529h-8.748v-2.9529zm-6.745 14.174v-15.19835c0-1.02737.2-1.8794.6-2.55611.406-.67671.959-1.18426 1.66-1.52261.702-.33836 1.498-.50753 2.39-.50753.603 0 1.154.04613 1.652.13842.505.09227.88.17532 1.126.24914l-.701 2.95293c-.154-.04922-.345-.09535-.572-.13842-.222-.04307-.449-.06459-.683-.06459-.578 0-.981.13534-1.209.40602-.228.26454-.341.63672-.341 1.11657v15.12453zm11.574 5.2876c-.499 0-.966-.04-1.403-.1199-.431-.0739-.787-.1661-1.07-.277l.886-2.9437c.695.2154 1.279.2706 1.753.1663.48-.0986.858-.4678 1.135-1.1075l.231-.5998-5.085-14.58h4.134l2.935 10.409h.147l2.962-10.409 4.162.0184-5.509 15.6874c-.264.7567-.624 1.415-1.08 1.9747-.449.5661-1.018 1.0029-1.707 1.3104-.689.3137-1.519.4707-2.491.4707z" fill="#000"/><path d="m18.9306 6.53613h-17.994321v18.85127h17.994321z" fill="#000"/><path d="m25.5.823639h-12.2819v12.281861h12.2819z" fill="#37c2a2"/></svg>
                </div>

                <h3 className="text-xl md:leading-3">{__('You\'re out of imports','extendify-sdk')}</h3>
                <p className="text-sm text-black">
                    {__('Sign up today and get unlimited access to our entire collection of patterns and page layouts.','extendify-sdk')}
                </p>
                <div>
                    <Primary
                        tagName="a"
                        target="_blank"
                        className="m-auto mt-10"
                        href={`https://extendify.com/pricing/?utm_source=${window.extendifySdkData.sdk_partner}&utm_medium=library&utm_campaign=no-imports-modal&utm_content=get-unlimited-imports`}
                        rel="noreferrer">
                        {__('Get Unlimited Imports','extendify-sdk')}
                        <svg fill="none" height="24" viewBox="0 0 25 24" width="25" xmlns="http://www.w3.org/2000/svg"><path d="m10.3949 8.7864 5.5476-.02507m0 0-.0476 5.52507m.0476-5.52507c-2.357 2.35707-5.4183 5.41827-7.68101 7.68097" stroke="currentColor" strokeWidth="1.5"/></svg>
                    </Primary>
                </div>
            </>}
            right={
                <div className="space-y-2">
                    <div className="flex items-center space-x-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fillRule="evenodd" clipRule="evenodd" d="M7.49271 18.0092C6.97815 17.1176 7.28413 15.9755 8.17569 15.4609C9.06724 14.946 10.2094 15.252 10.7243 16.1435C11.2389 17.0355 10.9329 18.1772 10.0413 18.6922C9.14978 19.2071 8.00764 18.9011 7.49271 18.0092V18.0092Z" fill="currentColor"/>
                            <path fillRule="evenodd" clipRule="evenodd" d="M16.5073 6.12747C17.0218 7.01903 16.7158 8.16117 15.8243 8.67573C14.9327 9.19066 13.7906 8.88467 13.2757 7.99312C12.7611 7.10119 13.0671 5.95942 13.9586 5.44449C14.8502 4.92956 15.9923 5.23555 16.5073 6.12747V6.12747Z" fill="currentColor"/>
                            <path fillRule="evenodd" clipRule="evenodd" d="M4.60135 11.1355C5.11628 10.2439 6.25805 9.93793 7.14998 10.4525C8.04153 10.9674 8.34752 12.1096 7.83296 13.0011C7.31803 13.8927 6.17588 14.1987 5.28433 13.6841C4.39278 13.1692 4.08679 12.0274 4.60135 11.1355V11.1355Z" fill="currentColor"/>
                            <path fillRule="evenodd" clipRule="evenodd" d="M19.3986 13.0011C18.8837 13.8927 17.7419 14.1987 16.85 13.6841C15.9584 13.1692 15.6525 12.027 16.167 11.1355C16.682 10.2439 17.8241 9.93793 18.7157 10.4525C19.6072 10.9674 19.9132 12.1092 19.3986 13.0011V13.0011Z" fill="currentColor"/>
                            <path d="M9.10857 8.92594C10.1389 8.92594 10.9742 8.09066 10.9742 7.06029C10.9742 6.02992 10.1389 5.19464 9.10857 5.19464C8.0782 5.19464 7.24292 6.02992 7.24292 7.06029C7.24292 8.09066 8.0782 8.92594 9.10857 8.92594Z" fill="currentColor"/>
                            <path d="M14.8913 18.942C15.9217 18.942 16.7569 18.1067 16.7569 17.0763C16.7569 16.046 15.9217 15.2107 14.8913 15.2107C13.8609 15.2107 13.0256 16.046 13.0256 17.0763C13.0256 18.1067 13.8609 18.942 14.8913 18.942Z" fill="currentColor"/>
                            <path fillRule="evenodd" clipRule="evenodd" d="M10.3841 13.0011C9.86951 12.1096 10.1755 10.9674 11.067 10.4525C11.9586 9.93793 13.1007 10.2439 13.6157 11.1355C14.1302 12.0274 13.8242 13.1692 12.9327 13.6841C12.0411 14.1987 10.899 13.8927 10.3841 13.0011V13.0011Z" fill="currentColor"/>
                        </svg>
                        <span className="text-sm leading-none">{__('Access to 100\'s of Patterns','extendify-sdk')}</span>
                    </div>
                    <div className="flex items-center space-x-2">
                        <svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><g stroke="currentColor" strokeWidth="1.5"><path d="m6 4.75h12c.6904 0 1.25.55964 1.25 1.25v12c0 .6904-.5596 1.25-1.25 1.25h-12c-.69036 0-1.25-.5596-1.25-1.25v-12c0-.69036.55964-1.25 1.25-1.25z"/><path d="m9.25 19v-14"/></g></svg>
                        <span className="text-sm leading-none">{__('Beautiful full page layouts','extendify-sdk')}</span>
                    </div>
                    <div className="flex items-center space-x-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="7.25" stroke="currentColor" strokeWidth="1.5"/>
                            <circle cx="12" cy="12" r="4.25" stroke="currentColor" strokeWidth="1.5"/>
                            <circle cx="11.9999" cy="12.2" r="6" transform="rotate(-45 11.9999 12.2)" stroke="currentColor" strokeWidth="3" strokeDasharray="1.5 4"/>
                        </svg>

                        <span className="text-sm leading-none">{__('Fast and friendly support','extendify-sdk')}</span>
                    </div>
                    <div className="flex items-center space-x-2">
                        <svg fill="none" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="m11.7758 3.45425c.0917-.18582.3567-.18581.4484 0l2.3627 4.78731c.0364.07379.1068.12493.1882.13676l5.2831.76769c.2051.02979.287.28178.1386.42642l-3.8229 3.72637c-.0589.0575-.0858.1402-.0719.2213l.9024 5.2618c.0351.2042-.1793.36-.3627.2635l-4.7254-2.4842c-.0728-.0383-.1598-.0383-.2326 0l-4.7254 2.4842c-.18341.0965-.39776-.0593-.36274-.2635l.90247-5.2618c.01391-.0811-.01298-.1638-.0719-.2213l-3.8229-3.72637c-.14838-.14464-.0665-.39663.13855-.42642l5.28312-.76769c.08143-.01183.15182-.06297.18823-.13676z" fill="currentColor"/></svg>
                        <span className="text-sm leading-none">{__('14-Day guarantee','extendify-sdk')}</span>
                    </div>
                </div>
            }
        /> }
    </>
}
