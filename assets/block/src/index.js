/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { registerBlockType } from "@wordpress/blocks";

/**
 * Internal dependencies
 */
import editor from "./editor";
import render from "./render";

/**
 * Register block
 */
export default registerBlockType("bctt/clicktotweet", {
  title: __("Better Click to Tweet"),
  description: __(
    "Add text for your readers to tweet, calling them to action on your behalf."
  ),
  category: "widgets",
  icon: "twitter",
  keywords: [__("Twitter"), __("Tweet")],
  supports: {
    align: false,
    alignWide: false
  },
  edit: editor,
  save: render
});
