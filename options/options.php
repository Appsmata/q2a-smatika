<?php
    if ( !defined( 'QA_VERSION' ) ) { // don't allow this page to be requested directly from browser
        header( 'Location: ../../' );
        exit;
    }
    /**
     * This file will contain all the option names we are going to use in out theme
     */

    if ( !class_exists( 'Smatika_Option_Keys' ) ) {
        class Smatika_Option_Keys
        {
            const THEME_VERSION = 'smatika_theme_ver';
            const INSTALLED_THEME_VERSION = 'smatika_theme_ver_instaled';
            const CDN_ENABLED = 'smatika_cdn_active';
            const BS_CSS_CDN = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css';
            const BS_THEME_CSS_CDN = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css';
            const FA_CDN = '//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css';
            const BS_JS_CDN = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js';
        }
    }

    /**
     * Class Smatika_Options
     */
    class Smatika_Options
    {
        /**
         * @var
         */
        protected static $instance;

        protected $config;
        protected $systemConfig;
        protected $userConfig;

        /**
         * @return Smatika_Options
         */
        public static function getInstance()
        {
            return isset( self::$instance ) ? self::$instance : self::$instance = new self();
        }

        /**
         * Constructor function
         */
        final private function __construct()
        {
            self::init();
        }

        protected function init()
        {
            $this->systemConfig = require 'system-defaults-options.php';
            $this->userConfig = require 'user-options.php';

            $this->config = array_merge( $this->systemConfig, $this->userConfig );
        }

        public function getConfig( $key )
        {
            return isset( $this->config[ $key ] ) ? $this->config[ $key ] : '';
        }
    }

    /**
     *
     * Reads the configuration file
     *
     * @param $key
     *
     * @return string
     *
     * @deprecated
     */
    function smatika_opt( $key )
    {
        return Smatika_Options::getInstance()->getConfig( strtolower($key) );
    }
