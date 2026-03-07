/**
 * Post sidebar: "Suggest X Content" button.
 *
 * Uses the BCTT abilities: suggest-tweetables (get suggestions) then inserts
 * the first suggestion as a Better Click to Tweet block at the end of the post.
 * Auth: cookie + nonce (wp.apiFetch); no Application Password in code.
 */
(function () {
	'use strict';

	// Prefer wp.editor (WP 6.6+); fallback to wp.editPost for older WP.
	var PluginDocumentSettingPanel =
		typeof wp !== 'undefined' && wp.editor && wp.editor.PluginDocumentSettingPanel
			? wp.editor.PluginDocumentSettingPanel
			: typeof wp !== 'undefined' && wp.editPost && wp.editPost.PluginDocumentSettingPanel
				? wp.editPost.PluginDocumentSettingPanel
				: null;

	if (typeof wp === 'undefined' || !wp.plugins || !PluginDocumentSettingPanel) {
		return;
	}

	var registerPlugin = wp.plugins.registerPlugin;
	var Button = wp.components.Button;
	var CheckboxControl = wp.components.CheckboxControl;
	var el = wp.element.createElement;
	var createInterpolateElement = wp.element.createInterpolateElement;
	var __ = wp.i18n.__;
	var useState = wp.element.useState;
	var useSelect = wp.data.useSelect;
	var useDispatch = wp.data.useDispatch;
	var apiFetch = wp.apiFetch;
	var createBlock = wp.blocks.createBlock;
	var serialize = wp.blocks.serialize;
	var select = wp.data.select;
	var dispatch = wp.data.dispatch;

	var suggestRunPath = '/wp-abilities/v1/abilities/bctt/suggest-tweetables/run';

	// From wp_localize_script: hasLlm, connectorsUrl, connectorUsageAgreed, userCanConnect, settingsUrl.
	var config = typeof window.bctt_suggest_config !== 'undefined' ? window.bctt_suggest_config : { hasLlm: false, connectorsUrl: '', connectorUsageAgreed: false, userCanConnect: false, settingsUrl: '' };
	var agreementApiPath = '/bctt/v1/connector-agreement';

	// X (Twitter) logo SVG for panel and plugin icon.
	function XIcon() {
		return el(
			'svg',
			{
				width: '24',
				height: '24',
				viewBox: '0 0 24 24',
				role: 'img',
				'aria-hidden': 'true',
				focusable: 'false',
			},
			el('path', {
				fill: 'currentColor',
				d: 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z',
			})
		);
	}

	function SuggestXContentPanel() {
		var isBusyState = useState(false);
		var isBusy = isBusyState[0];
		var setBusy = isBusyState[1];
		var agreedState = useState(config.connectorUsageAgreed);
		var agreed = agreedState[0];
		var setAgreed = agreedState[1];
		var canUseAi = config.hasLlm && (config.connectorUsageAgreed || agreed);

		var postId = useSelect(function (sel) {
			return sel('core/editor') && sel('core/editor').getCurrentPostId();
		}, []);

		function saveConnectorAgreement(checked) {
			setAgreed(checked);
			apiFetch({
				method: 'POST',
				path: agreementApiPath,
				data: { agreed: !!checked },
			}).catch(function (err) {
				setAgreed(!checked);
				// eslint-disable-next-line no-console
				console.error('BCTT: Failed to save connector agreement.', err);
			});
		}

		function runSuggestAndInsert(useAi) {
			if (!postId || isBusy) return;

			setBusy(true);

			apiFetch({
				method: 'POST',
				path: suggestRunPath,
				data: { input: { post_id: postId, use_ai: !!useAi } },
			})
				.then(function (suggestions) {
					if (!Array.isArray(suggestions) || suggestions.length === 0) {
						// eslint-disable-next-line no-console
						console.warn('BCTT: No tweetable suggestions returned.');
						return;
					}
					var first = suggestions[0];
					var text = first && first.text;
					if (!text) return;

					var blockEditor = select('core/block-editor');
					var currentBlocks = (blockEditor && blockEditor.getBlocks && blockEditor.getBlocks()) || [];
					var newBlock = createBlock('bctt/clicktotweet', { tweet: text });
					var allBlocks = currentBlocks.concat([newBlock]);
					var content = serialize(allBlocks);
					var editorDispatch = dispatch('core/editor');
					if (editorDispatch && editorDispatch.editPost) {
						editorDispatch.editPost({ content: content });
					}
				})
				.catch(function (err) {
					// eslint-disable-next-line no-console
					console.error('BCTT Suggest X Content:', err);
				})
				.finally(function () {
					setBusy(false);
				});
		}

		return el(
			PluginDocumentSettingPanel,
			{
				name: 'bctt-suggest-x-content',
				title: __('Suggest X Content', 'better-click-to-tweet'),
				icon: XIcon(),
			},
			el(
				'div',
				{ className: 'bctt-suggest-x-content-panel' },
				canUseAi
					? el(
							'div',
							{ style: { marginBottom: '12px' } },
							el(
								Button,
								{
									variant: 'secondary',
									className: 'is-full-width',
									isBusy: isBusy,
									disabled: isBusy || !postId,
									onClick: function () { runSuggestAndInsert(true); },
									style: { width: '100%', justifyContent: 'center' },
								},
								__('Add Suggested Content (AI)', 'better-click-to-tweet')
							),
							el(
								'p',
								{
									className: 'editor-post-card-panel__description editor-post-content-information bctt-suggest-ai-disclaimer',
									style: { marginTop: '4px', marginBottom: 0 },
								},
								createInterpolateElement(
									__('NOTE: Using the above button may incur usage charges from your <link>Connected AI model</link>. Better Click to Tweet is not responsible for any charges.', 'better-click-to-tweet'),
									{
										link: el('a', {
											href: config.connectorsUrl,
											target: '_blank',
											rel: 'noopener noreferrer',
										}, 'Connector'),
									}
								)
							),
							config.userCanConnect && config.settingsUrl
								? el(
										'p',
										{
											className: 'editor-post-card-panel__description editor-post-content-information',
											style: { marginTop: '8px', marginBottom: 0 },
										},
										createInterpolateElement(
											__('Disable AI features for all users <link>here</link>.', 'better-click-to-tweet'),
											{
												link: el('a', {
													href: config.settingsUrl + '#bctt-connector-usage-agreed',
													target: '_blank',
													rel: 'noopener noreferrer',
												}, __('here', 'better-click-to-tweet')),
											}
										)
									)
								: null
						)
					: null,
				el(
					Button,
					{
						variant: canUseAi ? 'tertiary' : 'secondary',
						className: 'is-full-width',
						isBusy: isBusy,
						disabled: isBusy || !postId,
						onClick: function () { runSuggestAndInsert(false); },
						style: {
							width: '100%',
							justifyContent: 'center',
							marginTop: canUseAi ? '8px' : 0,
						},
					},
					__('Add Suggested Content (from post)', 'better-click-to-tweet')
				),
				// Agreement / connect block: not connected and can connect, or connected but not yet agreed
				(!config.hasLlm && config.userCanConnect && config.connectorsUrl)
					? el(
							'div',
							{ className: 'bctt-connect-to-ai-block', style: { marginTop: '12px' } },
							el(
								'p',
								{
									className: 'editor-post-card-panel__description editor-post-content-information',
									style: { marginBottom: '8px' },
								},
								__(
									'Did you know? WordPress now allows you to officially connect your entire site to various LLMs (Large Language Models) to use Artificial Intelligence directly from within your site!',
									'better-click-to-tweet'
								)
							),
							el(
								'p',
								{
									className: 'editor-post-card-panel__description editor-post-content-information',
									style: { marginBottom: '8px' },
								},
								createInterpolateElement(
									__(
										'Better Click To Share can leverage that with "Bill" (the cheeky name we\'ve given our AI assistant) as a Senior Social Media Marketer. Once you connect your provider on the <link>Connectors</link> page, Bill will read your post, suggest an engaging post for X using your connected AI model, and insert it into the post for your readers to click!',
										'better-click-to-tweet'
									),
									{
										link: el('a', {
											href: config.connectorsUrl,
											target: '_blank',
											rel: 'noopener noreferrer',
										}, __('Connectors', 'better-click-to-tweet')),
									}
								)
							),
							el(
								'p',
								{
									className: 'editor-post-card-panel__description editor-post-content-information',
									style: { marginBottom: '12px' },
								},
								__(
									'IMPORTANT Note: Any user with post-editing access can use AI suggestions once you accept responsibility for usage charges. Usage charges from your connected model apply all post editors on your site.',
									'better-click-to-tweet'
								)
							),
							el(
								CheckboxControl,
								{
									label: __(
										'By checking this box, you agree that the decision of which AI/LLM model you connect to and any charges incurred from that model are solely your responsibility.',
										'better-click-to-tweet'
									),
									checked: agreed,
									onChange: saveConnectorAgreement,
								}
							),
							el(
								'div',
								{ style: { marginTop: '16px' } },
								el(
									Button,
									{
										variant: 'secondary',
										className: 'is-full-width',
										href: agreed ? config.connectorsUrl : undefined,
										target: '_blank',
										rel: 'noopener noreferrer',
										disabled: !agreed,
										style: { width: '100%', justifyContent: 'center' },
									},
									__('Connect to AI', 'better-click-to-tweet')
								)
							)
						)
					: (config.hasLlm && !canUseAi)
						? el(
								'div',
								{ className: 'bctt-connect-to-ai-block', style: { marginTop: '12px' } },
								el(
									'p',
									{
										className: 'editor-post-card-panel__description editor-post-content-information',
										style: { marginBottom: '8px' },
									},
									__(
										'A model is already connected. To use Bill\'s AI suggestions, please confirm that you accept responsibility for usage charges. Usage charges from your connected model apply all post editors on your site.',
										'better-click-to-tweet'
									)
								),
								el(
									CheckboxControl,
									{
										label: __(
											'By checking this box, you agree that the decision of which AI/LLM model you connect to and any charges incurred from that model are solely your responsibility and you will not hold Better Click to Tweet responsible for any usage charges.',
											'better-click-to-tweet'
										),
										checked: agreed,
										onChange: saveConnectorAgreement,
									}
								)
							)
						: !config.hasLlm && !config.userCanConnect
							? el(
									'p',
									{
										className: 'editor-post-card-panel__description editor-post-content-information',
										style: { marginTop: '12px', marginBottom: '8px' },
									},
									__(
										'Did you know? Your site could be utilizing "Bill" (our helpful AI assistant) to suggest engaging content for X. AI suggestions are available when an administrator connects a model in Settings → Connectors. Reach out to a site admin to enable this feature!',
										'better-click-to-tweet'
									)
								)
								
							: null
			)
		);
	}

	registerPlugin('bctt-suggest-x-content', {
		render: SuggestXContentPanel,
		icon: XIcon(),
	});
})();
