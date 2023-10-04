export const Title = ({ title, description }) => (
    <div className="w-full relative max-w-xl mx-auto">
        <div className="flex flex-col gap-2 mb-8 md:mb-12">
            <h2 className="text-2xl md:text-3xl leading-8 md:leading-10 m-0 text-gray-900 text-center">
                {title}
            </h2>
            <p className="text-gray-700 text-base text-center leading-6 m-0">
                {description}
            </p>
        </div>
    </div>
)
