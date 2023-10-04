import { useState } from '@wordpress/element'
import { Icon } from '@wordpress/icons'
import { Answer } from '@chat/components/dialog/Answer'
import { useHistoryStore } from '@chat/state/Global'
import { arrow } from '@chat/svg'

export const History = ({ reset }) => {
    const { history } = useHistoryStore()
    const [questionToShow, setQuestionToShow] = useState(null)

    return (
        <>
            {questionToShow ? (
                <Answer
                    question={questionToShow.question}
                    answer={questionToShow.htmlAnswer}
                    reset={reset}
                />
            ) : (
                <div className="py-6 h-96 overflow-scroll">
                    <div className="relative">
                        {[...history]
                            .sort((a, b) => a.time - b.time)
                            .map((item) => (
                                <button
                                    className="bg-white border-0 border-b border-gray-100 border-solid cursor-pointer flex gap-4 group hover:bg-gray-100 items-center justify-between last:border-b-0 p-3 px-6 text-gray-800 w-full"
                                    key={item.answerId}
                                    onClick={() => setQuestionToShow(item)}>
                                    <span className="overflow-ellipsis overflow-hidden truncate">
                                        {item.question.substring(0, 100)}
                                    </span>
                                    <span>
                                        <Icon
                                            className="fill-current text-gray-200 group-hover:text-gray-600"
                                            icon={arrow}
                                        />
                                    </span>
                                </button>
                            ))}
                    </div>
                </div>
            )}
        </>
    )
}
