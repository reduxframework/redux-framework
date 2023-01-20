import { QuickLinks } from '@assist/components/dashboard/QuickLinks'
import { Recommendations } from '@assist/components/dashboard/Recommendations'
import { TasksList } from '@assist/components/dashboard/TasksList'
import { Full } from './layouts/Full'

export const Dashboard = () => {
    return (
        <Full>
            <TasksList />
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 items-start mt-0 lg:mt-4">
                <QuickLinks />
                <Recommendations />
            </div>
        </Full>
    )
}
