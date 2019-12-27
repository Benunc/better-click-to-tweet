/**
 * External dependecies
 */
import classnames from "classnames";

/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { Fragment } from "@wordpress/element";
import { RichText } from "@wordpress/editor";

/**
 * Internal dependencies
 */
import Inspector from "./inspector";

/**
 * Block edit component
 */
const editor = props => {
  const { attributes, setAttributes, className } = props;
  const { tweet, prompt } = attributes;
  // Events
  const onChangeTweet = value => {
    setAttributes({ tweet: value });
  };

  const onClickPrompt = () => {
    return false;
  };
  // Render block editor
  return (
    <Fragment>
      <Inspector {...{ ...props }} />

      <span className={classnames(className, "bctt-click-to-tweet")}>
        <span className="bctt-ctt-text">
          <RichText
            format="string"
            formattingControls={[]}
            tagName="div"
            placeholder={__("Enter text for readers to Tweet")}
            onChange={onChangeTweet}
            value={tweet}
          />
        </span>
        <a href="#" onClick={onClickPrompt} className="bctt-ctt-btn">
          {prompt}
        </a>
      </span>
    </Fragment>
  );
};

export default editor;
