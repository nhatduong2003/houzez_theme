import { createBlock, pasteHandler } from '@wordpress/blocks'
import {
    MenuGroup,
    MenuItem,
    __experimentalDivider as Divider,
} from '@wordpress/components'
import { useDispatch, useSelect } from '@wordpress/data'
import { useEffect } from '@wordpress/element'
import { __ } from '@wordpress/i18n'
import { Icon } from '@wordpress/icons'
import { useContentHighlight } from '@draft/hooks/useContentHighlight'
import { replace, replay, below, trash, shorter, longer } from '@draft/svg'

export const InsertMenu = ({
    prompt,
    completion,
    loading,
    setPrompt,
    setInputText,
}) => {
    const { toggleHighlight, toggleInsertionPoint } = useContentHighlight()
    const { insertBlocks, replaceBlocks } = useDispatch('core/block-editor')
    const selectedBlock = useSelect(
        (select) => select('core/block-editor').getSelectedBlock(),
        [],
    )
    const selectedBlockClientIds = useSelect(
        (select) => select('core/block-editor').getSelectedBlockClientIds(),
        [],
    )
    const { getBlockRootClientId, getBlockIndex, getBlock } = useSelect(
        (select) => select('core/block-editor'),
        [],
    )

    const canReplaceContent = () => {
        const targetBlock = selectedBlock
            ? selectedBlock
            : getBlock(selectedBlockClientIds[0])
        if (!targetBlock) {
            return true
        }
        return typeof targetBlock?.attributes?.content === 'undefined'
    }

    const transformBlocks = (blocks, targetBlockId) => {
        const targetBlock = getBlock(targetBlockId)
        return blocks.map((block) => {
            return {
                ...block,
                name: targetBlock.name,
                attributes: {
                    ...targetBlock.attributes,
                    content: block.attributes.content,
                },
            }
        })
    }

    const plainTextToBlocks = (plainText) => {
        let blocks = pasteHandler({ plainText: plainText })
        if (!Array.isArray(blocks)) {
            blocks = [
                createBlock('core/paragraph', {
                    content: blocks,
                }),
            ]
        }
        return blocks
    }

    const insertCompletion = ({ replaceContent = false }) => {
        setPrompt({ text: '', promptType: '', systemMessageKey: '' })

        const targetBlockId = selectedBlock
            ? selectedBlock?.clientId
            : selectedBlockClientIds[0]

        let blocks = plainTextToBlocks(completion)

        if (selectedBlock && selectedBlock?.attributes?.content === '') {
            replaceContent = true
        }

        if (replaceContent) {
            blocks = transformBlocks(blocks, targetBlockId)
            replaceBlocks(selectedBlockClientIds, blocks)
        } else if (!targetBlockId) {
            insertBlocks(blocks)
        } else {
            const parentBlockId = getBlockRootClientId(targetBlockId)
            const blockIndex = getBlockIndex(
                selectedBlockClientIds.at(-1),
                parentBlockId,
            )
            if (parentBlockId) {
                blocks = transformBlocks(blocks, targetBlockId)
            }
            insertBlocks(blocks, blockIndex + 1, parentBlockId)
        }
    }

    const handleEdit = (promptType) => {
        setInputText('')
        setPrompt({
            text: completion,
            promptType,
            systemMessageKey: 'edit',
        })
    }

    const discard = () => {
        setInputText('')
        setPrompt({ text: '', promptType: '', systemMessageKey: '' })
    }

    const retry = () => {
        setInputText('')
        setPrompt({ text: '', promptType: '', systemMessageKey: '' })
        setTimeout(() => setPrompt(prompt))
    }

    useEffect(() => {
        return () => {
            toggleHighlight(selectedBlockClientIds, { isHighlighted: false })
        }
    }, [selectedBlockClientIds, toggleHighlight])

    return (
        <MenuGroup>
            <MenuItem
                onClick={() => insertCompletion({ replaceContent: true })}
                onMouseEnter={() =>
                    toggleHighlight(selectedBlockClientIds, {
                        isHighlighted: true,
                    })
                }
                onMouseLeave={() =>
                    toggleHighlight(selectedBlockClientIds, {
                        isHighlighted: false,
                    })
                }
                disabled={loading || canReplaceContent()}>
                <Icon
                    icon={replace}
                    className="flex-shrink-0 fill-current w-5 h-5 mr-2"
                />
                <span className="whitespace-normal text-left">
                    {__('Replace selected block text', 'extendify')}
                </span>
            </MenuItem>
            <MenuItem
                onClick={() => insertCompletion({ replaceContent: false })}
                onMouseEnter={() => toggleInsertionPoint(true)}
                onMouseLeave={() => toggleInsertionPoint(false)}
                disabled={loading}>
                <Icon
                    icon={below}
                    className="flex-shrink-0 fill-current w-5 h-5 mr-2"
                />
                <span className="whitespace-normal text-left">
                    {__('Insert below', 'extendify')}
                </span>
            </MenuItem>
            <MenuItem
                onClick={() => handleEdit('make-shorter')}
                disabled={loading}
                className="group">
                <Icon
                    icon={shorter}
                    className="flex-shrink-0 fill-current w-5 h-5 mr-2"
                />
                {__('Make shorter', 'extendify')}
            </MenuItem>
            <MenuItem
                onClick={() => handleEdit('make-longer')}
                disabled={loading}
                className="group">
                <Icon
                    icon={longer}
                    className="flex-shrink-0 fill-current w-5 h-5 mr-2"
                />
                <span className="whitespace-normal text-left">
                    {__('Make longer', 'extendify')}
                </span>
            </MenuItem>
            <Divider />
            <MenuItem onClick={retry} disabled={loading}>
                <Icon
                    icon={replay}
                    className="flex-shrink-0 fill-current w-5 h-5 mr-2"
                />
                <span className="whitespace-normal text-left">
                    {__('Try again', 'extendify')}
                </span>
            </MenuItem>
            <MenuItem onClick={discard} disabled={loading}>
                <Icon
                    icon={trash}
                    className="flex-shrink-0 fill-current w-5 h-5 mr-2"
                />
                <span className="whitespace-normal text-left">
                    {__('Discard', 'extendify')}
                </span>
            </MenuItem>
        </MenuGroup>
    )
}
