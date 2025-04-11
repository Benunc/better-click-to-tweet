/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { InspectorControls } from "@wordpress/block-editor";
import { PanelBody, TextControl, ToggleControl } from "@wordpress/components";

/**
 * Create an Inspector Controls wrapper Component
 */
const Inspector = ({ attributes, setAttributes }) => {
  const { username, via, url, urlcustom, nofollow, prompt } = attributes;

  const updateAttribute = (name, value) => {
    setAttributes({ [name]: value });
  };

  return (
    <InspectorControls>
      <PanelBody title={__("General", "better-click-to-tweet")}>
        <TextControl
          label={__("Twitter Username", "better-click-to-tweet")}
          value={username}
          onChange={(value) => updateAttribute("username", value)}
        />
        <ToggleControl
          label={__("Include an X username in shared post?", "better-click-to-tweet")}
          checked={via}
          onChange={(value) => updateAttribute("via", value)}
        />

        <TextControl
          label={__("Prompt", "better-click-to-tweet")}
          value={prompt}
          onChange={(value) => updateAttribute("prompt", value)}
          help={__("Text for action/prompt link", "better-click-to-tweet")}
        />
      </PanelBody>
      <PanelBody title={__("URL", "better-click-to-tweet")} initialOpen={false}>
        <ToggleControl
          label={__("Include URL in shared post?", "better-click-to-tweet")}
          checked={url}
          onChange={(value) => updateAttribute("url", value)}
        />
        <TextControl
          label={__("Custom URL", "better-click-to-tweet")}
          value={urlcustom}
          onChange={(value) => updateAttribute("urlcustom", value)}
          help={__("Custom URL to use instead of post", "better-click-to-tweet")}
        />
        <ToggleControl
          label={__("Nofollow", "better-click-to-tweet")}
          checked={nofollow}
          onChange={(value) => updateAttribute("nofollow", value)}
          help={__("Make links nofollow", "better-click-to-tweet")}
        />
      </PanelBody>
    </InspectorControls>
  );
};

export default Inspector;
