/**
 * Transform shortcode to block
 */
const tranformer = {
  from: [
    {
      type: "shortcode",
      tag: "bctt",
      attributes: {
        tweet: {
          type: "string",
          shortcode: ({ named: { tweet } }) => {
            return tweet;
          }
        },
        username: {
          type: "string",
          shortcode: ({ named: { username } }) => {
            return username;
          }
        },
        via: {
          type: "boolean",
          shortcode: ({ named: { via } }) => {
            return "yes" === via;
          }
        },
        url: {
          type: "boolean",
          shortcode: ({ named: { url } }) => {
            return "yes" === url;
          }
        },
        urlcustom: {
          type: "string",
          shortcode: ({ named: { urlcustom } }) => {
            return urlcustom;
          }
        },
        nofollow: {
          type: "boolean",
          shortcode: ({ named: { nofollow } }) => {
            return "yes" === nofollow;
          }
        },
        prompt: {
          type: "string",
          shortcode: ({ named: { prompt } }) => {
            return prompt;
          }
        }
      }
    }
  ]
};

export default tranformer;
