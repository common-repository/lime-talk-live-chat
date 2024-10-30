<?php
    /**
     * Plugin Name: Lime Talk Live Chat
     * Description: Start chatting with your website visitors. This plug-in allows you to easily insert Lime Talk live chat on your website.
     * Version: 1.0.1
     * Author: Lime Talk
     * Author URI: https://www.limetalk.com/
     * License: GPLv2 or later
     */

    if( !defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

    if( !class_exists( 'LNCPluginBase' ) ) {
        require_once __DIR__ . '/lib/pluginBase.php';
    }

    class LimeTalkPlugin extends LNCPluginBase {

        const PLUGIN_ID   = 'limetalk';
        const PLUGIN_NAME = 'Live Chat';

        public function __construct() {

            parent::__construct();

            add_action(
                    'wp_head',
                    array(
                            $this,
                            'hookHeader'
                    ),
                    15
            );
        }

        public function hookHeader() {
            echo $this->getPluginOption( 'head' ) . "\n";
        }

        public function hookAdminMenu() {
            add_menu_page(
                    __(
                            self::PLUGIN_NAME,
                            self::PLUGIN_ID
                    ),
                    __(
                            self::PLUGIN_NAME,
                            self::PLUGIN_ID
                    ),
                    'manage_options',
                    self::PLUGIN_ID . '-admin-page-settings',
                    array(
                            $this,
                            'buildAdminPageSettings'
                    ),
                    'dashicons-format-chat',
                    80
            );

        }

        public function buildAdminPageSettings() {

            ?>
            <form action='options.php' method='post'>

                <div class="wrap" style="max-width: 580px">

                    <h2>
                        <strong>
                            <?php echo __(
                                    'Set up your Lime Talk Account',
                                    self::PLUGIN_ID
                            ) ?>
                        </strong>
                    </h2>

                    <?php settings_errors(); ?>

                    <p>
                        <?php echo sprintf(
                                __(
                                        'If you don\'t have a Lime Talk account already, please %s. It takes just a few seconds.',
                                        self::PLUGIN_ID
                                ),
                                sprintf(
                                        '<a href="%s" target="_blank">%s</a>',
                                        esc_attr( 'https://www.limetalk.com/en/sign/up' ),
                                        __(
                                                'register here',
                                                self::PLUGIN_ID
                                        )
                                )

                        ) ?>
                    </p>

                    <p>
                        <label style="cursor: default" for="<?php echo $this->pluginSettingsKey ?>_head">
                            <?php echo __(
                                    'Copy the installation code from the Lime Talk client zone and paste it here:',
                                    self::PLUGIN_ID
                            ) ?>
                        </label>
                    </p>

                    <p>
                        <textarea id="<?php echo $this->pluginSettingsKey ?>_head" rows="10"
                                  name='<?php echo $this->pluginSettingsKey ?>[head]'
                                  style="width: 100%;"><?php echo esc_attr(
                                    $this->getPluginOption( 'head' )
                            ) ?></textarea>
                    </p>

                    <?php

                        settings_fields( $this->pluginSettingsKey );
                        do_settings_sections( self::PLUGIN_ID . '-admin-page-settings' );

                    ?>
                    <p>
                        <?php
                            submit_button(
                                    'Save',
                                    'secondary alignright',
                                    'submit',
                                    false
                            );
                        ?>
                    </p>

                    <br class="clear">

                    <p>
                        <?php echo sprintf(
                                __(
                                        'That is all. Live chat should be visible on your site now (if you don\'t see it, reload the page). Accept chats and reply to your visitors from the %s.',
                                        self::PLUGIN_ID
                                ),
                                sprintf(
                                        '<a href="%s" target="_blank">%s</a>',
                                        esc_attr( 'https://www.limetalk.com/client' ),
                                        'Lime Talk client zone'
                                )
                        )?>
                    </p>
                </div>

            </form>
        <?php

        }

    }

    $LimeTalkPlugin = new LimeTalkPlugin();