[...document.querySelectorAll(".block-editor-iframe__body a, .block-editor-iframe__body button")].forEach(clickable => {
    clickable.addEventListener("click", e => {
        e.preventDefault();
    });
});

/**
 * Sync inner block classes in Gutenberg editor to match frontend styling
 */
wp.domReady(function() {
    if (!wp.hooks || !wp.compose || !wp.element || !wp.data) {
        return;
    }

    const { addFilter } = wp.hooks;
    const { createHigherOrderComponent } = wp.compose;
    const { createElement } = wp.element;
    
    const blockClassMappings = window.themeInnerBlockClasses || {};
    
    if (!blockClassMappings || Object.keys(blockClassMappings).length === 0) {
        return;
    }

    /**
     * Filter the block list block to add classes in the editor view
     */
    addFilter(
        'editor.BlockListBlock',
        'theme/inner-blocks-class-sync',
        createHigherOrderComponent((BlockListBlock) => {
            return function(props) {
                const { name, clientId } = props;
                
                // Check if block editor APIs are available
                if (!wp.data.select('core/block-editor')) {
                    return createElement(BlockListBlock, props);
                }
                
                const { getBlockParents, getBlock } = wp.data.select('core/block-editor');
                const parents = getBlockParents(clientId);
                
                // Check all parent blocks to see if any have class mappings
                for (const parentId of parents) {
                    const parentBlock = getBlock(parentId);
                    if (!parentBlock) continue;
                    
                    const mappings = blockClassMappings[parentBlock.name];
                    if (!mappings || !mappings[name]) continue;
                    
                    const customClassName = mappings[name];
                    if (customClassName) {
                        props = {
                            ...props,
                            className: `${props.className || ''} ${customClassName}`.trim()
                        };
                    }
                }
                
                return createElement(BlockListBlock, props);
            };
        }, 'withInnerBlocksClassSync')
    );
});
