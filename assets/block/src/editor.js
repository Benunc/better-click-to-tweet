/**
 * External dependencies
 */
import classnames from 'classnames';

/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { useEffect, useRef } from '@wordpress/element';
import { RichText, useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { store as editorStore } from '@wordpress/editor';

/**
 * Internal dependencies
 */
import Inspector from './inspector';

/**
 * Block edit component
 */
const Editor = (props) => {
  const { attributes, setAttributes } = props;
  const { tweet, prompt } = attributes;
  const timerRef = useRef(null);

  // Get the post title using the new useSelect hook
  const title = useSelect(
    (select) => select(editorStore).getEditedPostAttribute('title'),
    []
  );

  // Set default tweet text when component mounts or title changes
  useEffect(() => {
    if (!tweet && title) {
      setAttributes({ tweet: title });
    }
  }, [tweet, title, setAttributes]);

  // Events
  const onChangeTweet = (value) => {
    if (timerRef.current) {
      clearTimeout(timerRef.current);
    }

    if (!value) {
      timerRef.current = setTimeout(() => {
        setAttributes({ tweet: title });
      }, 3000);
    } else {
      setAttributes({ tweet: value });
    }
  };

  const onClickPrompt = (e) => {
    e.preventDefault();
    return false;
  };

  // Get block props with className
  const blockProps = useBlockProps({
    className: 'bctt-click-to-tweet',
  });

  // Render block editor
  return (
    <>
      <Inspector {...props} />

      <div {...blockProps}>
        <span className="bctt-ctt-text">
          <RichText
            format="string"
            allowedFormats={[]}
            tagName="div"
            placeholder={__('Enter text for readers to Share on X', 'better-click-to-tweet')}
            onChange={onChangeTweet}
            value={tweet}
          />
        </span>
        <a href="#" onClick={onClickPrompt} className="bctt-ctt-btn">
          {prompt}
        </a>
      </div>
    </>
  );
};

export default Editor;
