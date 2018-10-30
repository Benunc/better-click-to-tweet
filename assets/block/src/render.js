/**
 * Wordpress dependencies
 */
import { RawHTML } from "@wordpress/element";

/**
 * Internal dependencies
 */
import { booleanToYesNo } from "./utils";

/**
 * Block save component
 */
const render = ({ attributes }) => {
  const { tweet, username, via, url, urlcustom, nofollow, prompt } = attributes;

  const shortcode = `[bctt 
                          tweet="${tweet}" 
                          url="${urlcustom || booleanToYesNo(url)}" 
                          via="${booleanToYesNo(via)}" 
                          username="${username}" 
                          nofollow="${booleanToYesNo(nofollow)}" 
                          prompt="${prompt}"
                      ]`;

  return <RawHTML>{shortcode}</RawHTML>;
};

export default render;
