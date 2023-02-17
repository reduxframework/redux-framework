import { QuickLinks } from '@assist/components/dashboard/QuickLinks'
import { Recommendations } from '@assist/components/dashboard/Recommendations'
import { SupportArticles } from '@assist/components/dashboard/SupportArticles'
import { TasksList } from '@assist/components/dashboard/TasksList'
import { Tours } from '@assist/components/dashboard/Tours'
import { Full } from './layouts/Full'

export const Dashboard = () => {
    return (
        <Full>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 items-start mt-0 lg:mt-4">
                <TasksList />
                <SupportArticles />
                <Tours />
                <Recommendations />
                <QuickLinks />
            </div>
        </Full>
    )
}
