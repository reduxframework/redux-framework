import { Icon } from '@wordpress/components'
import { useLayoutEffect, useState, useEffect } from '@wordpress/element'
import { __, sprintf } from '@wordpress/i18n'
import classNames from 'classnames'
import { motion, AnimatePresence } from 'framer-motion'
import { useDesignColors } from '@assist/hooks/useDesignColors'
import { useEditorHeightAdjust } from '@assist/hooks/useEditorHeightAdjust'
import { useTasks } from '@assist/hooks/useTasks'
import { useTaskbarStore } from '@assist/state/Taskbar'
import { useTasksStore } from '@assist/state/Tasks'
import { LogoIcon } from '@assist/svg'
import { TaskHead } from './task-items/TaskHead'
import { TaskItem } from './task-items/TaskItem'

export const TaskbarBody = () => {
    const { open } = useTaskbarStore()
    const [offset, setOffset] = useState(0)
    const [transitioning, setTransitioning] = useState(true)
    const { darkColor } = useDesignColors()
    const { availableTasks, isCompleted } = useTasksStore()
    const { tasks } = useTasks()
    const taskCount =
        availableTasks?.filter((t) => !isCompleted(t))?.length ?? 0
    const [current, setCurrent] = useState(0)
    const notCompleted = tasks?.filter((t) => !isCompleted(t.slug))
    useEditorHeightAdjust({ open, leaveDelay: 300 })

    const handleNext = () => {
        setTransitioning(true)
        setCurrent((cur) => (cur + 1 === taskCount ? cur : cur + 1))
    }
    const handlePrev = () => {
        setTransitioning(true)
        setCurrent((cur) => (cur === 0 ? cur : cur - 1))
    }

    useLayoutEffect(() => {
        const handle = () => {
            // get the height of the admin bar
            setOffset(document.getElementById('wpadminbar')?.offsetHeight ?? 0)
        }
        handle()
        addEventListener('resize', handle)
        return () => removeEventListener('resize', handle)
    }, [open])

    useEffect(() => {
        if (!taskCount) return
        // if the tasks count changes (they complete/add one)
        // and they are out of bound, reset the current to the end
        if (current > taskCount - 1) {
            setCurrent(taskCount - 1)
        }
        // If they dismiss/complete the current task, it's moving
        setTransitioning(true)
    }, [taskCount, current])

    return (
        <>
            <div
                className={classNames(
                    'hidden md:block transition-all transform duration-300 ease-in-out h-28',
                    { '-mt-28': !open },
                )}
            />
            <section
                style={{
                    top: `${offset}px`,
                    transitionProperty: 'transform', // prevents offset bleed
                    backgroundColor: darkColor,
                }}
                className={classNames(
                    'assist-taskbar hidden md:flex transform duration-300 ease-in-out fixed h-28 left-0 w-full z-high bg-design-dark border-b border-solid border-white border-opacity-25',
                    {
                        '-translate-y-full': !open,
                        'overflow-hidden': transitioning,
                    },
                )}>
                <a
                    className="w-28 flex items-center justify-center"
                    href={
                        window.extAssistData.adminUrl +
                        'admin.php?page=extendify-assist#dashboard'
                    }>
                    <LogoIcon className="w-8 h-8" />
                </a>

                <div className="flex-grow bg-design-main relative flex justify-center items-center">
                    <TasksList
                        tasks={notCompleted}
                        current={current}
                        onTransitionEnd={() => setTransitioning(false)}
                    />
                </div>

                <ButtonControl
                    current={current}
                    totalCount={taskCount}
                    onNext={handleNext}
                    onPrev={handlePrev}
                />
                {tasks?.map((task) => (
                    // prerender tasks hidden so they self report
                    // a bit hacky for now
                    <TaskHead task={task} key={task.slug} />
                ))}
            </section>
        </>
    )
}

const TasksList = ({ tasks, current, onTransitionEnd }) => {
    if (!tasks) {
        return (
            <div className="text-base flex items-center text-white h-full px-8 max-w-screen-md2">
                {__('Loading tasks...', 'extendify')}
            </div>
        )
    }
    if (!tasks?.length) {
        return (
            <div className="text-base flex items-center justify-between text-white h-full px-8 w-full">
                <div>
                    <h2 className="text-white text-md m-0 mb-2">
                        {__('All caught up!', 'extendify')}
                    </h2>
                    <p className="m-0 p-0 text-white text-base">
                        {__(
                            'Take a break or explore some recommendations to improve site even further!',
                            'extendify',
                        )}
                    </p>
                </div>
                {/* <a
                    href={
                        window.extAssistData.adminUrl +
                        'admin.php?page=extendify-assist#recommendations'
                    }
                    className="px-4 py-3 text-white text-xs border-0 rounded cursor-pointer bg-gray-900 text-center no-underline">
                    {__('See recommendations', 'extendify')}
                </a> */}
            </div>
        )
    }

    if (!tasks[current]) return null

    return (
        <AnimatePresence>
            <motion.div
                key={tasks[current].slug}
                initial={{ opacity: 0, y: 100 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: -100 }}
                transition={{ duration: 0.3 }}
                onAnimationComplete={onTransitionEnd}
                className="flex justify-between items-center w-full absolute px-8 gap-4 max-w-screen-md2">
                <TaskItem className="text-white flex" task={tasks[current]} />
            </motion.div>
        </AnimatePresence>
    )
}

const ButtonControl = ({ current, totalCount, onNext, onPrev }) => {
    if (totalCount === 0) return null
    return (
        <div className="px-8 flex items-center justify-end w-full max-w-sm">
            <div className="flex gap-4 items-center">
                <button
                    onClick={onPrev}
                    type="button"
                    disabled={current === 0}
                    className={classNames(
                        'border border-white bg-transparent text-white p-2 m-0 text-sm cursor-pointer flex justify-between items-center gap-1 hover:bg-white hover:bg-opacity-20',
                        {
                            'opacity-0': current === 0,
                        },
                    )}>
                    <Icon icon="arrow-left-alt2" className="text-xs h-4 w-4" />
                    {__('Previous', 'extendify')}
                </button>
                <a
                    style={{ minWidth: '75px' }} // avoid common 1 -> 2 layout shift
                    href={
                        window.extAssistData.adminUrl +
                        'admin.php?page=extendify-assist#tasks'
                    }
                    className="bg-transparent text-white p-1 px-2 m-0 text-sm hidden cursor-pointer lg:flex flex-col justify-between items-center no-underline hover:bg-white hover:bg-opacity-20 text-center leading-snug">
                    {sprintf(
                        // translators: %1$s: current task number, %2$s: total tasks
                        __('Task %1$s/%2$s', 'extendify'),
                        current + 1,
                        totalCount,
                    )}
                    <br />
                    <span className="text-xss block">
                        {__('View all', 'extendify')}
                    </span>
                </a>
                <button
                    onClick={onNext}
                    type="button"
                    disabled={current === totalCount - 1}
                    className={classNames(
                        'border border-white bg-transparent text-white p-2 m-0 text-sm cursor-pointer flex justify-between items-center gap-1 hover:bg-white hover:bg-opacity-20',
                        {
                            'opacity-0': current === totalCount - 1,
                        },
                    )}>
                    {__('Next', 'extendify')}
                    <Icon icon="arrow-right-alt2" className="text-xs h-4 w-4" />
                </button>
            </div>
        </div>
    )
}
