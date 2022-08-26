import { useState, useEffect } from '@wordpress/element'
import { Transition } from '@headlessui/react'
import classNames from 'classnames'
import Color from 'color'
import Skeleton, { SkeletonTheme } from 'react-loading-skeleton'
import 'react-loading-skeleton/dist/skeleton.css'

export const SkeletonLoader = ({ theme, context }) => {
    const [highlightColor, setHighlightColor] = useState()
    const color = theme?.color ?? '#000000'
    const bgColor = theme?.bgColor ?? '#cccccc'

    useEffect(() => {
        const id = requestAnimationFrame(() => {
            if (!theme?.color) return
            const hl = Color(color).isLight()
                ? darkenBy(Color(color), 0.15).hexa()
                : lightenBy(Color(color), 0.15).hexa()
            setHighlightColor(hl)
        })
        return () => cancelAnimationFrame(id)
    }, [color, bgColor, theme?.color])

    return (
        <div
            className={classNames({
                'group w-full overflow-hidden relative min-h-full button-focus button-card':
                    context === 'style',
            })}>
            <Transition
                appear={true}
                show={!highlightColor}
                leave="transition-opacity duration-1000"
                leaveFrom="opacity-100"
                leaveTo="opacity-0"
                className="absolute inset-0 z-10 bg-white">
                <div
                    className={classNames({
                        'm-2 p-2 pt-1': context === 'style',
                        'p-2': context !== 'style',
                    })}>
                    <SkeletonParts
                        highlightColor="hsl(0deg 0% 75%)"
                        color="hsl(0deg 0% 80%)"
                    />
                </div>
            </Transition>
            {/* If a theme is passed in, render a second skeleton under it so we can fade into it */}
            {Boolean(highlightColor) && (
                <div
                    className="overflow-hidden absolute inset-0 opacity-30"
                    style={{
                        zIndex: -1,
                    }}>
                    <div
                        className={classNames({
                            'm-2 p-2 pt-1': context === 'style',
                            'p-2': context !== 'style',
                        })}
                        style={{
                            backgroundColor: bgColor,
                            textAlign: 'initial',
                        }}>
                        <SkeletonParts
                            highlightColor={highlightColor}
                            color={color}
                        />
                    </div>
                </div>
            )}
        </div>
    )
}

const SkeletonParts = ({ color, highlightColor }) => {
    return (
        <SkeletonTheme
            duration={2.3}
            baseColor={color}
            highlightColor={highlightColor}>
            <Skeleton className="h-36 mb-5 rounded-none" />
            <div className="flex flex-col items-center">
                <div>
                    <Skeleton className="w-28 h-4 mb-1 rounded-none" />
                </div>
                <div>
                    <Skeleton className="w-44 h-4 mb-1 rounded-none" />
                </div>
                <div>
                    <Skeleton className="w-12 h-6 mb-1 rounded-none" />
                </div>
            </div>
            <div className="px-4">
                <Skeleton className="h-24 my-5 rounded-none" />
                <div className="flex justify-between gap-4">
                    <div>
                        <div>
                            <Skeleton className="w-40 h-4 mb-1 rounded-none" />
                        </div>
                        <div>
                            <Skeleton className="w-40 h-4 mb-1 rounded-none" />
                        </div>
                        <div>
                            <Skeleton className="w-40 h-4 mb-1 rounded-none" />
                        </div>
                    </div>
                    <div>
                        <div>
                            <Skeleton className="w-24 h-4 mb-1 rounded-none" />
                        </div>
                        <div>
                            <Skeleton className="w-24 h-4 mb-1 rounded-none" />
                        </div>
                    </div>
                </div>
            </div>
        </SkeletonTheme>
    )
}

const lightenBy = (color, ratio) => {
    const lightness = color.lightness()
    return color.lightness(lightness + (100 - lightness) * ratio)
}

const darkenBy = (color, ratio) => {
    const lightness = color.lightness()
    return color.lightness(lightness - lightness * ratio)
}
