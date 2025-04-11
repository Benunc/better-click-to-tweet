/**
 * WordPress dependencies
 */
import { registerBlockType } from "@wordpress/blocks";

/**
 * Internal dependencies
 */
import editor from "./editor";
import render from "./render";
import metadata from "../block.json";

/**
 * Register block
 */
registerBlockType(metadata, {
  edit: editor,
  save: render
});
