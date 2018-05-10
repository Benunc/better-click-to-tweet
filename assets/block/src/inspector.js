/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { InspectorControls } from "@wordpress/blocks";
import { PanelBody, TextControl, ToggleControl } from "@wordpress/components";

/**
 * Create an Inspector Controls wrapper Component
 */
const Inspector = ({ attributes, setAttributes }) => {
  const { username, via, url, urlcustom, nofollow, prompt } = attributes;

  // Inspector control events
  const onChangeUsername = value => {
    setAttributes({ username: value });
  };
  const toggleVia = () => {
    setAttributes({ via: !via });
  };
  const toggleUrl = () => {
    setAttributes({ url: !url });
  };
  const onChangeUrlCustom = value => {
    setAttributes({ urlcustom: value });
  };
  const toggleNoFollow = () => {
    setAttributes({ nofollow: !nofollow });
  };
  const onChangePrompt = value => {
    setAttributes({ prompt: value });
  };
  return (
    <InspectorControls key="inspector">
      <PanelBody Title={__("Tweet Settings")}>
        <TextControl
          label={__("Twitter Username")}
          value={username}
          onChange={onChangeUsername}
        />
        <ToggleControl
          label={__("Include the username in Tweet?")}
          checked={!!via}
          onChange={toggleVia}
        />
        <ToggleControl
          label={__("Include URL in tweet?")}
          checked={!!url}
          onChange={toggleUrl}
        />
        <TextControl
          label={__("Custom URL")}
          value={urlcustom}
          onChange={onChangeUrlCustom}
          help={__("Custom URL to use instead of post")}
        />
        <ToggleControl
          label={__("Nofollow")}
          checked={!!nofollow}
          onChange={toggleNoFollow}
          help={__("Make links nofollow")}
        />
        <TextControl
          label={__("Prompt")}
          value={prompt}
          onChange={onChangePrompt}
          help={__("Text for action/prompt link")}
        />
      </PanelBody>
    </InspectorControls>
  );
};

export default Inspector;
