/**
 * Internal block libraries
 */
const { __ } = wp.i18n;

const blockAttributes = {
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
        default: __('Click To Tweet')
    },
}

export default blockAttributes;