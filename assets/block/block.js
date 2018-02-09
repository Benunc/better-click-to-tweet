/**
 * Block dependencies
 */
import classnames from 'classnames';
import blockAttributes from './attributes';
import Inspector from './inspector';

/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const {
    registerBlockType,
    Editable,
} = wp.blocks;

/**
 * Register block
 */
export default registerBlockType(
    'bctt/clicktotweet',
    {
        title: __('Better Click to Tweet'),
        description: __('The most popular click to tweet plugin for wordpress (by a mile), for good reason.'),
        category: 'widgets',
        icon: 'twitter',
        keywords: [
            __('Twitter'),
            __('Tweet'),
        ],
        attributes: blockAttributes,
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
                    <Inspector 
                        { ... { onChangeTweet, onChangeUsername, toggleVia, toggleUrl, onChangeUrlCustom, toggleNoFollow, onChangePrompt, onClickPrompt, ...props } }
                    />
                ),

                // Edit UI
                <span className={classnames(props.className, 'bctt-click-to-tweet')} key={props.className}>
                    <span class="bctt-ctt-text">
                        <Editable
                            tagName="a"
                            placeholder={__('Enter text for readers to Tweet')}
                            onChange={(onChangeTweet)}
                            value={props.attributes.tweet} 
                            focus={props.focus}
                            formattingControls={ [] }
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