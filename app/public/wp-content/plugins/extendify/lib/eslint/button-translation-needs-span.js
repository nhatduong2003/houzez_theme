const fix = (fixer, node, context) => {
    // wrap with span
    return fixer.replaceTextRange(
        [node.start, node.end],
        `<span>${context.getSourceCode().getText(node)}</span>`,
    )
}

module.exports = {
    meta: {
        docs: {
            description:
                'Browser auto-translation will break if pieces of text nodes are rendered conditionally.',
        },
        schema: [],
        messages: {
            dangerousConditional:
                'Conditionally rendered translation function with siblings must be wrapped in <div> or <span>',
            dangerousLiteral:
                'Translation function is a sibling of conditional expression and must be wrapped in <div> or <span>',
        },
        type: 'problem',
        fixable: 'code',
    },
    create: function (context) {
        return {
            // case 1: conditional text node:
            //   {conditional && __()}
            //   {conditional ? __() : null}
            //   {conditional ? __() : foo}
            //   {conditional ? __() : 'foo'}
            //   {conditional ? __() : <span>string</span>}
            ':matches(JSXElement, JSXFragment) > JSXExpressionContainer:matches([expression.type="ConditionalExpression"], [expression.type="LogicalExpression"])':
                (node) => {
                    const { expression, parent } = node
                    const siblingNodes = (parent.children || []).filter(
                        (n) =>
                            !(
                                ['JSXText', 'Literal'].includes(n.type) &&
                                // newlines followed by empty string doesn't create a sibling
                                !n.value.trim() &&
                                n.value.startsWith('\n')
                            ),
                    )
                    // only child is fine
                    if (siblingNodes.length <= 1) {
                        return
                    }
                    if (expression.type === 'LogicalExpression') {
                        if (expression.right?.callee?.name?.startsWith('__')) {
                            context.report({
                                node,
                                messageId: 'dangerousConditional',
                                fix: (fixer) => fix(fixer, node, context),
                            })
                        }
                    } else {
                        if (
                            [expression.consequent, expression.alternate].some(
                                (n) =>
                                    n?.expression?.callee?.name?.startsWith(
                                        '__',
                                    ),
                            )
                        ) {
                            context.report({
                                node,
                                messageId: 'dangerousConditional',
                                fix: (fixer) => fix(fixer, node, context),
                            })
                        }
                    }
                },
            // case 2: conditionally rendered JSX element followed by translation function:
            //  <div>
            //    {conditional && <span>text</span>}
            //    __()
            //  </div>
            'JSXExpressionContainer:matches([expression.type="LogicalExpression"][expression.right.type="JSXElement"], [expression.type="ConditionalExpression"]) + :not(:matches(JSXExpressionContainer))':
                (node) => {
                    const index = node.parent.children.indexOf(node)
                    // if empty text starting with newline, it's only dangerous if the following node can become a text node
                    const followingNode = node.parent.children[index + 1]
                    if (!node.value.trim() && node.value.startsWith('\n')) {
                        if (
                            !followingNode ||
                            followingNode.type !== 'JSXExpressionContainer'
                        ) {
                            return
                        }
                    }
                    if (
                        followingNode?.expression?.callee?.name?.startsWith(
                            '__',
                        )
                    ) {
                        context.report({
                            node: followingNode,
                            messageId: 'dangerousLiteral',
                            fix: (fixer) => fix(fixer, followingNode, context),
                        })
                    }
                },
        }
    },
}
