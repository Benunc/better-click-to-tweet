/**
 * Block dependencies
 */
import classnames from 'classnames';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const {
    registerBlockType,
    Editable,
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
 * Register block
 */
export default registerBlockType(
    'bctt/clicktotweet',
    {
        title: __('Better Click to Tweet'),
        category: 'widgets',
        icon: 'twitter',
        keywords: [
            __('Twitter'),
            __('Tweet'),
        ],
        attributes: {
            tweet: {
                type: 'string'
            },
            username: {
                type: 'string',
                default: bctt_options_js.username
            },
            via: {
                type: 'boolean',
                default: true
            },
            url: {
                type: 'boolean',
                default: true
            },
            urlcustom: {
                type: 'string',
            },
            nofollow: {
                type: 'boolean',
                default: false
            },
            prompt: {
                type: 'string',
                default: 'Click To Tweet'
            },
        },
        edit: props => {

            // Inspector control events
            const onChangeTweet = value => {
                props.setAttributes({ tweet: value[0] });
            };
            const onChangeUsername = value => {
                props.setAttributes({ username: value });
            };
            const toggleVia = () => {
                props.setAttributes({ via: !props.attributes.via });
            };
            const toggleUrl = () => {
                props.setAttributes({ url: !props.attributes.url });
            };
            const onChangeUrlCustom = value => {
                props.setAttributes({ urlcustom: value });
            };
            const toggleNoFollow = () => {
                props.setAttributes({ nofollow: !props.attributes.nofollow });
            };
            const onChangePrompt = value => {
                props.setAttributes({ prompt: value });
            };
            const onClickPrompt = () =>  {
                return false;
            };
           
            return [
                // Inspector Options
                !! props.focus && (
                    <InspectorControls key="inspector">
                        <PanelBody Title={__('Tweet Settings')}>
                            <TextControl
                                label={__('Username')}
                                value={props.attributes.username}
                                onChange={onChangeUsername}
                            />
                            <ToggleControl
                                label={__('Username in tweet')}
                                checked={( !! props.attributes.via  ) }
                                onChange={toggleVia}
                            />
                            <ToggleControl
                                label={__('Url in tweet')}
                                checked={(!!props.attributes.url)}
                                onChange={toggleUrl}
                            />
                            <TextControl
                                label={__('Custom URL')}
                                value={props.attributes.urlcustom}
                                onChange={onChangeUrlCustom}
                                help={__('Custom Url to use instead of post')}
                            />
                            <ToggleControl
                                label={__('No Follow')}
                                checked={(!!props.attributes.nofollow)}
                                onChange={toggleNoFollow}
                            />
                            <TextControl
                                label={__('Prompt')}
                                value={props.attributes.prompt}
                                onChange={onChangePrompt}
                                help={__('Text for action/prompt link')}
                            />
                            
                        </PanelBody>
                    </InspectorControls>
                ),

                // Block Edit UI
                <span className={classnames(props.className, 'bctt-click-to-tweet')} key={props.className}>
                    <span class="bctt-ctt-text">
                        <Editable
                            tagName="a"
                            placeholder={__('Enter Your Tweet')}
                            onChange={(onChangeTweet)}
                            value={props.attributes.tweet} 
                            focus={props.focus}
                        />
                    </span>
                    <a href="#" onClick={onClickPrompt} class="bctt-ctt-btn">
                        {props.attributes.prompt}
                    </a>
                </span>
            ];
        },
        save() {
            // Rendering shortcode using PHP callback
            return null;
        },
    },
);