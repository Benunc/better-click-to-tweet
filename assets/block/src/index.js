/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import { registerBlockType } from "@wordpress/blocks";

/**
 * Internal dependencies
 */
import blockAttributes from "./attributes";
import editor from "./editor";
import render from "./render";

/**
 * Register block
 */
export default registerBlockType("bctt/clicktotweet", {
  title: __("Better Click to Tweet"),
  description: __(
    "The most popular click to tweet plugin for wordpress (by a mile), for good reason."
  ),
  category: "widgets",
  icon: "twitter",
  keywords: [__("Twitter"), __("Tweet")],
  attributes: blockAttributes,
  edit: editor,
  save: render
});
