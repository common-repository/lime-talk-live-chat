<?php
    /**
     * Author: Lime Talk
     */

    if( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

    abstract class LNCPluginBase {

        const PLUGIN_ID   = '';
        const PLUGIN_NAME = '';

        protected $pluginKey;
        protected $pluginSettingsKey;

        public function __construct() {

            $this->pluginKey = str_replace(
                    ' ',
                    '',
                    ucwords(
                            str_replace(
                                    '-',
                                    ' ',
                                    static::PLUGIN_ID
                            )
                    )
            );

            $this->pluginSettingsKey = $this->pluginKey . 'Settings';


            $this->includes();

            add_action(
                    'plugins_loaded',
                    array(
                            $this,
                            'hookPluginsLoaded'
                    )
            );

            add_filter(
                    'rwmb_meta_boxes',
                    array(
                            $this,
                            'hookMetaBoxes'
                    ),
                    100,
                    1
            );

            add_action(
                    'admin_init',
                    array(
                            $this,
                            'hookAdminInit'
                    ),
                    100
            );

            add_action(
                    'admin_menu',
                    array(
                            $this,
                            'hookAdminMenu'
                    ),
                    100
            );

            add_action(
                    'admin_enqueue_scripts',
                    array(
                            $this,
                            'hookAdminEnqueueScripts'
                    )
            );


        }

        public function hookPluginsLoaded() {

            load_plugin_textdomain(
                    static::PLUGIN_ID,
                    false,
                    plugin_basename( dirname( __FILE__ ) ) . '/languages'
            );

        }

        public function hookAdminInit() {

            register_setting(
                    $this->pluginSettingsKey,
                    $this->pluginSettingsKey
            );

        }

        public function hookAdminMenu() {

        }

        public function hookAdminEnqueueScripts() {

            $path = '/' . static::PLUGIN_ID . '/css/admin.css';
            if( file_exists( WP_PLUGIN_DIR . $path ) ) {
                $k = sprintf(
                        '%s_admin_css',
                        static::PLUGIN_ID
                );
                wp_register_style(
                        $k,
                        plugins_url(
                                $path
                        ),
                        false,
                        '1.0.0'
                );
                wp_enqueue_style( $k );
            }
        }

        public function hookMetaBoxes( $meta_boxes ) {

            $result = array();
            foreach( $meta_boxes as $k => $meta_box ) {

                $result[] = $meta_box;
            }

            return $result;
        }

        public function includes() {

        }

        public function getPluginOption( $k ) {
            $options = get_option( $this->pluginSettingsKey );
            $options = array_merge(
                    $this->getDefaultOptions(),
                    is_array( $options )
                            ? $options
                            : array()
            );

            return $options[$k];
        }

        protected function getDefaultOptions() {
            return array();
        }

    }
