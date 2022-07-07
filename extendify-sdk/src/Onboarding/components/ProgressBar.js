export const ProgressBar = ({ currentPageIndex, totalPages }) => {
    const currentProgress = Math.round(
        ((currentPageIndex + 1) / totalPages) * 100,
    )
    const currentPageText = `(${currentPageIndex}/${totalPages - 1})`

    return (
        <div className="flex-1 hidden md:flex justify-center items-center">
            <div
                role="progressbar"
                aria-valuenow={currentProgress}
                aria-valuemin="0"
                aria-valuetext={currentPageText}
                aria-valuemax="100"
                className="w-32 bg-gray-200 h-2 rounded-full">
                <div
                    className="rounded-full bg-partner-primary-bg h-2"
                    style={{
                        width: `${currentProgress}%`,
                    }}></div>
            </div>
            <div className="pl-2 flex justify-center">{currentPageText}</div>
        </div>
    )
}
