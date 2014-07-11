<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( ! class_exists( 'Redux_Filesystem' ) ) {
        class Redux_Filesystem {
            private $parent = null;

            public $fs_object = null;

            public function __construct( $parent ) {
                $parent->filesystem = $this;
                $this->parent       = $parent;
            }

            public function execute( $action, $file, $params = '' ) {

                global $wp_filesystem;

                if ( ! empty ( $params ) ) {
                    extract( $params );
                }

                // Setup the filesystem with creds
                require_once( ABSPATH . '/wp-admin/includes/file.php' );
                require_once( ABSPATH . '/wp-admin/includes/file.php' );

                if ( $this->parent->args['menu_type'] == 'submenu' ) {
                    $page_parent = $this->parent->args['page_parent'];
                    $base        = $page_parent . '?page=' . $this->parent->args['page_slug'];
                } else {
                    $base = 'admin.php?page=' . $this->parent->args['page_slug'];
                }

                $url = wp_nonce_url( $base );
                if ( ! isset( $this->creds ) ) {
                    $this->creds = request_filesystem_credentials( $url, 'direct', false, false );
                    if ( ! $this->creds && is_admin() && !isset( $this->filesystem->killswitch ) ) {
                        $this->creds = request_filesystem_credentials( $url, '', false, false );
                    }
                }

                if ( false === $this->creds ) {
                    return true;
                }

                if ( ! WP_Filesystem( $this->creds ) ) {
                    return true;
                }

                $wp_filesystem =& $wp_filesystem;

                // Do unique stuff
                if ( $action == 'mkdir' && !isset( $this->filesystem->killswitch ) ) {
                    $res = $wp_filesystem->$action( $file, 0755 );
                } elseif ( $action == 'copy' && !isset( $this->filesystem->killswitch ) ) {
                    $res = false;
                } elseif ( $action == 'put_contents' && !isset( $this->filesystem->killswitch ) ) {
                    $wp_filesystem->put_contents( $file, $content, 0644 );
                    $res = false;
                } elseif ( $action == 'get_contents' ) {
                    $res = $wp_filesystem->$action( $file );
                } elseif ( $action == 'object' ) {
                    $res = $wp_filesystem;
                } else {
                    $res = false;
                }

                return $res;
            }
        }
    }
