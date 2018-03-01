/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const {
    InspectorControls,
} = wp.blocks;
const {
    PanelBody,
    TextControl,
    ToggleControl,
} = wp.components;

/**
* Create an Inspector Controls wrapper Component
*/
const Inspector = (props) => {
    return(
        <InspectorControls key="inspector">
            <PanelBody Title={__('Tweet Settings')}>
                <TextControl
                    label={__('Twitter Username')}
                    value={props.attributes.username}
                    onChange={props.onChangeUsername}
                />
                <ToggleControl
                    label={__('Include the username in Tweet?')}
                    checked={(!!props.attributes.via)}
                    onChange={props.toggleVia}
                />
                <ToggleControl
                    label={__('Include URL in tweet?')}
                    checked={(!!props.attributes.url)}
                    onChange={props.toggleUrl}
                />
                <TextControl
                    label={__('Custom URL')}
                    value={props.attributes.urlcustom}
                    onChange={props.onChangeUrlCustom}
                    help={__('Custom URL to use instead of post')}
                />
                <ToggleControl
                    label={__('Nofollow')}
                    checked={(!!props.attributes.nofollow)}
                    onChange={props.toggleNoFollow}
                    help={__('Make links nofollow')}
                />
                <TextControl
                    label={__('Prompt')}
                    value={props.attributes.prompt}
                    onChange={props.onChangePrompt}
                    help={__('Text for action/prompt link')}
                />

            </PanelBody>
        </InspectorControls>
    )
}

export default Inspector;