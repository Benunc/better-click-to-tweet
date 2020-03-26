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
      <PanelBody title={__("General")}>
        <TextControl
          label={__("Twitter Username")}
          value={username}
          onChange={value => updateAttribute("username", value)}
        />
        <ToggleControl
          label={__("Include the username in Tweet?")}
          checked={via}
          onChange={value => updateAttribute("via", value)}
        />

        <TextControl
          label={__("Prompt")}
          value={prompt}
          onChange={value => updateAttribute("prompt", value)}
          help={__("Text for action/prompt link")}
        />
      </PanelBody>
      <PanelBody title={__("URL")} initialOpen={false}>
        <ToggleControl
          label={__("Include URL in tweet?")}
          checked={url}
          onChange={value => updateAttribute("url", value)}
        />
        <TextControl
          label={__("Custom URL")}
          value={urlcustom}
          onChange={value => updateAttribute("urlcustom", value)}
          help={__("Custom URL to use instead of post")}
        />
        <ToggleControl
          label={__("Nofollow")}
          checked={nofollow}
          onChange={value => updateAttribute("nofollow", value)}
          help={__("Make links nofollow")}
        />
      </PanelBody>
    </InspectorControls>
  );
};

export default Inspector;
