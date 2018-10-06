<?php
/**
 * Get plugin info including status, type, and license validation.
 *
 * This is an enhanced version of get_plugins() that returns the status
 * (`active` or `inactive`) of all plugins, type of plugin (`add-on` or `other`
 * and license validation for Better Click To Tweet add-ons (`true` or `false`). Does not include
 * MU plugins. This function is borrowed from the Give Plugin.
 *
 * @since 5.6
 *
 * @return array Plugin info plus status, and type if
 *               available.
 */

function bctt_get_active_addons() {
	$plugins             = get_plugins();
	$active_plugin_paths = (array) get_option( 'active_plugins', array() );

	if ( is_multisite() ) {
		$network_activated_plugin_paths = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
		$active_plugin_paths            = array_merge( $active_plugin_paths, $network_activated_plugin_paths );
	}

	foreach ( $plugins as $plugin_path => $plugin_data ) {
		// Is plugin active?
		if ( in_array( $plugin_path, $active_plugin_paths ) ) {
			$plugins[ $plugin_path ]['Status'] = 'active';
		} else {
			$plugins[ $plugin_path ]['Status'] = 'inactive';
		}

		$dirname = strtolower( dirname( $plugin_path ) );

		// Is plugin a Better Click To Tweet add-on by Better Click To Tweet?
		if ( strstr( $dirname, 'better-click' ) && strstr( $plugin_data['AuthorURI'], 'betterclicktotweet.com' ) ) {
			// Plugin is a Better Click To Tweet add-on.
			$plugins[ $plugin_path ]['Type'] = 'add-on';

		} else {
			// Plugin is not a Better Click To Tweet add-on.
			$plugins[ $plugin_path ]['Type'] = 'other';
		}

	}

	foreach ( $plugins as $key => $plugin_data ) {
		if ( 'active' != $plugin_data['Status'] || 'add-on' != $plugin_data['Type'] ) {
			unset( $plugins[ $key ] );
		}
		if ( 'Better Click To Tweet' === $plugin_data['Name'] ) {
			unset( $plugins[ $key ] );
		}

	}

	return $plugins;
}

function bctt_addon_shortname( $addonname ) {
	$shortname = trim( str_replace( 'Better Click To Tweet ', '', $addonname ) );

	return $shortname;
}

function bctt_addon_slug( $shortname ) {

	$slug = str_replace( ' ', '_', strtolower( $shortname ) );

	return $slug;

}