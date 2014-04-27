<?php

/**
 * Plugin Name: TIVWP-DM Development Manager
 * Plugin URI: https://github.com/TIVWP/tivwp-dm
 * Description: Install and manage development plugins. (Single-site only, no Network Activation).
 * Text Domain: tivwp-dm
 * Domain Path: /languages/
 * Version: 14.03.25
 * Author: TIV.NET
 * Author URI: http://www.tiv.net
 * Network: false
 * License: GPL2
 */
/*  Copyright 2014 Gregory Karpinsky (tiv.net)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * There is nothing in this plugin for WP AJAX calls,
 * so we cut this off right away, even before loading our classes.
 */
if (defined('DOING_AJAX') && DOING_AJAX) {
    return;
}

/**
 * Disable network activation on multisite.
 * @param bool $network_wide
 */
function tivwp_dm_disable_network_activation($network_wide) {
    if ($network_wide) {
        $silent = true;
        deactivate_plugins(plugin_basename(__FILE__), $silent, $network_wide);
        wp_redirect(network_admin_url('plugins.php?deactivate=true'));
        exit;
    }
}

/**
 * There should be no reason to use this plugin network-wide.
 * However, if anyone wants that, there is a constant that allows:
 * <code>
 * define( 'TIVWP_DM_NETWORK_ACTIVATION_ALLOWED', true );
 * </code>
 */
if (!( defined('TIVWP_DM_NETWORK_ACTIVATION_ALLOWED') && TIVWP_DM_NETWORK_ACTIVATION_ALLOWED )) {
    register_activation_hook(__FILE__, 'tivwp_dm_disable_network_activation');
}


/**
 * Launch the Controller only after plugins_loaded, so we can do necessary validation
 * @see TIVWP_DM_Controller::construct
 */
require_once dirname(__FILE__) . '/includes/class-tivwp-dm-controller.php';
add_action('plugins_loaded', array(
    'TIVWP_DM_Controller',
    'construct'
        )
);

# --- EOF
