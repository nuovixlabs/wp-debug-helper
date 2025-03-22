<?php
/**
 * Plugin Name: WP Debug Helper
 * Description: A simple plugin to manage WordPress debugging via WP-CLI.
 * Version: 1.0
 * Author: Rakesh Mandal
 * Author URI: https://rakeshmandal.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Make sure WP-CLI is active
if (defined('WP_CLI') && WP_CLI) {

    /**
     * Manage WordPress debugging via WP-CLI.
     */
    class Debug_Commands extends WP_CLI_Command {

        /**
         * Enable WordPress debugging.
         *
         * ## OPTIONS
         * 
         * [--display]
         * : Enable displaying errors on screen. Default is false.
         * 
         * ## EXAMPLES
         * 
         *     # Enable debugging with logging but no display
         *     $ wp debug enable
         * 
         *     # Enable debugging with both logging and display
         *     $ wp debug enable --display
         */
        public function enable($args, $assoc_args) {
            $display = isset($assoc_args['display']) ? true : false;
            
            WP_CLI::run_command(array('config', 'set', 'WP_DEBUG', 'true'), array('raw' => true));
            WP_CLI::run_command(array('config', 'set', 'WP_DEBUG_LOG', 'true'), array('raw' => true));
            WP_CLI::run_command(array('config', 'set', 'WP_DEBUG_DISPLAY', $display ? 'true' : 'false'), array('raw' => true));
            
            WP_CLI::success('WordPress debugging enabled. Logs will be saved to wp-content/debug.log');
            if ($display) {
                WP_CLI::warning('Errors will be displayed on screen. This is not recommended for production sites.');
            }
        }

        /**
         * Disable WordPress debugging.
         * 
         * ## EXAMPLES
         * 
         *     # Disable all debugging
         *     $ wp debug disable
         */
        public function disable($args, $assoc_args) {
            WP_CLI::run_command(array('config', 'set', 'WP_DEBUG', 'false'), array('raw' => true));
            WP_CLI::run_command(array('config', 'set', 'WP_DEBUG_LOG', 'false'), array('raw' => true));
            WP_CLI::run_command(array('config', 'set', 'WP_DEBUG_DISPLAY', 'false'), array('raw' => true));
            
            WP_CLI::success('WordPress debugging disabled.');
        }

        /**
         * Check WordPress debugging status.
         * 
         * ## EXAMPLES
         * 
         *     # Check debug status
         *     $ wp debug status
         */
        public function status($args, $assoc_args) {
            $debug = WP_CLI::runcommand('config get WP_DEBUG', array('return' => true));
            $debug_log = WP_CLI::runcommand('config get WP_DEBUG_LOG', array('return' => true));
            $debug_display = WP_CLI::runcommand('config get WP_DEBUG_DISPLAY', array('return' => true));
            
            WP_CLI::log("WordPress Debug Status:");
            WP_CLI::log("WP_DEBUG: " . ($debug === '1' ? 'Enabled' : 'Disabled'));
            WP_CLI::log("WP_DEBUG_LOG: " . ($debug_log === '1' ? 'Enabled' : 'Disabled'));
            WP_CLI::log("WP_DEBUG_DISPLAY: " . ($debug_display === '1' ? 'Enabled' : 'Disabled'));
            
            // Check if log file exists and show its size
            $log_file = WP_CONTENT_DIR . '/debug.log';
            if (file_exists($log_file)) {
                $size = size_format(filesize($log_file));
                WP_CLI::log("Debug log file: Exists ($size)");
            } else {
                WP_CLI::log("Debug log file: Does not exist");
            }
        }

        /**
         * Test if debugging is working by generating a test log entry.
         * 
         * ## EXAMPLES
         * 
         *     # Test debug logging
         *     $ wp debug test
         */
        public function test($args, $assoc_args) {
            $debug = WP_CLI::runcommand('config get WP_DEBUG', array('return' => true));
            $debug_log = WP_CLI::runcommand('config get WP_DEBUG_LOG', array('return' => true));
            
            if ($debug !== '1' || $debug_log !== '1') {
                WP_CLI::error('Debugging is not fully enabled. Run "wp debug enable" first.');
                return;
            }
            
            $test_message = 'WP-CLI Debug Test: ' . date('Y-m-d H:i:s');
            error_log($test_message);
            
            WP_CLI::success('Test log entry written: "' . $test_message . '"');
            WP_CLI::log('You can view the log with: wp debug view');
        }

        /**
         * View the debug log file.
         * 
         * ## OPTIONS
         * 
         * [--tail=<lines>]
         * : Show only the last specified number of lines
         * 
         * [--clear]
         * : Clear the log file after viewing
         * 
         * ## EXAMPLES
         * 
         *     # View the entire debug log
         *     $ wp debug view
         * 
         *     # View only the last 20 lines
         *     $ wp debug view --tail=20
         * 
         *     # View and then clear the log
         *     $ wp debug view --clear
         */
        public function view($args, $assoc_args) {
            $log_file = WP_CONTENT_DIR . '/debug.log';
            
            if (!file_exists($log_file)) {
                WP_CLI::error('Debug log file does not exist yet.');
                return;
            }
            
            $content = file_get_contents($log_file);
            
            if (empty($content)) {
                WP_CLI::log('Debug log file is empty.');
            } else {
                if (isset($assoc_args['tail'])) {
                    $lines = explode("\n", $content);
                    $tail = intval($assoc_args['tail']);
                    $lines = array_slice($lines, -$tail);
                    $content = implode("\n", $lines);
                    WP_CLI::log("Showing last $tail lines of debug log:");
                }
                
                WP_CLI::log("\n--- DEBUG LOG START ---\n");
                WP_CLI::log($content);
                WP_CLI::log("\n--- DEBUG LOG END ---\n");
            }
            
            if (isset($assoc_args['clear'])) {
                file_put_contents($log_file, '');
                WP_CLI::success('Debug log file cleared.');
            }
        }
    }
    
    WP_CLI::add_command('debug', 'Debug_Commands');
}