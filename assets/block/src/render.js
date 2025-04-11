/**
 * WordPress dependencies
 */
import { useBlockProps } from '@wordpress/block-editor';

/**
 * Block save component
 *
 * We're using dynamic rendering via PHP, so this returns null
 */
const Render = () => {
  // We still need to use useBlockProps to ensure proper block attributes are saved
  useBlockProps.save();
  return null;
};

export default Render;
