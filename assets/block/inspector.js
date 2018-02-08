/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { Component } = wp.element;
const {
    InspectorControls,
} = wp.blocks;
const {
	PanelBody
} = wp.components;
const {
    TextControl,
    ToggleControl,
} = InspectorControls;

/**
* Create an Inspector Controls wrapper Component
*/
export default class Inspector extends Component {
    constructor(props) {
        super(...arguments);
    }

    render() {
        return(
            <InspectorControls key="inspector">
                <PanelBody Title={__('Tweet Settings')}>
                    <TextControl
                        label={__('Username')}
                        value={this.props.attributes.username}
                        onChange={this.props.onChangeUsername}
                    />
                    <ToggleControl
                        label={__('Username in tweet')}
                        checked={(!!this.props.attributes.via)}
                        onChange={this.props.toggleVia}
                    />
                    <ToggleControl
                        label={__('Url in tweet')}
                        checked={(!!this.props.attributes.url)}
                        onChange={this.props.toggleUrl}
                    />
                    <TextControl
                        label={__('Custom URL')}
                        value={this.props.attributes.urlcustom}
                        onChange={this.props.onChangeUrlCustom}
                        help={__('Custom Url to use instead of post')}
                    />
                    <ToggleControl
                        label={__('No Follow')}
                        checked={(!!this.props.attributes.nofollow)}
                        onChange={this.props.toggleNoFollow}
                    />
                    <TextControl
                        label={__('Prompt')}
                        value={this.props.attributes.prompt}
                        onChange={this.props.onChangePrompt}
                        help={__('Text for action/prompt link')}
                    />

                </PanelBody>
            </InspectorControls>
        )
    }

}
