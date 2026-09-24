/**
 * Editor Configuration
 * 
 * Gutenberg editor setup and customizations
 */

wp.domReady(function() {
    // Hide block patterns library if not needed
    // Can be customized as needed
    
    // Customize editor options
    wp.data.dispatch('core/editor').setPreviewDeviceType('Desktop');
});

// Customize allowed inline styles
wp.hooks.addFilter(
    'blocks.getBlockAttributes',
    'atora/customize-block-attributes',
    function(attributes, blockType) {
        // Allow custom classes on all blocks
        return attributes;
    }
);
