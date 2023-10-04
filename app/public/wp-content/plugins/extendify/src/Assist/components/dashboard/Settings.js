import { createSlotFill } from '@wordpress/components'
import { __ } from '@wordpress/i18n'

const { Slot } = createSlotFill('Extendify/Assist/Settings')

export const Settings = () => (
    <Slot>
        {(fills) =>
            fills.length > 0 && (
                <div
                    id="assist-settings-module"
                    className="extendify-assist-settings w-full border border-gray-300 p-4 md:p-8 bg-white rounded mt-6">
                    <h2 className="text-lg leading-tight m-0">
                        {__('Settings', 'extendify')}
                    </h2>
                    {fills}
                </div>
            )
        }
    </Slot>
)
