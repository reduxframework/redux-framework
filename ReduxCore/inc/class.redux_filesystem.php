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
                add_action( 'all_admin_notices', array( $this, 'ftp_form' ) );
            }

            public function ftp_form() {
                if ( isset( $this->ftp_form ) && ! empty( $this->ftp_form ) ) {
                    echo '<div class="wrap"><div class="error"><p>';
                    echo __( 'Unable to create a required directory. Please ensure that', 'redux-framework' );
                    echo ' <code>' . Redux_Helpers::cleanFilePath( trailingslashit( WP_CONTENT_DIR ) ) . '/uploads/</code> ';
                    echo __( 'has the proper read/write permissions or enter your FTP information below.', 'redux-framework' );
                    echo '</p></div><h2></h2>' . $this->ftp_form . '</div>';
                }
            }

            public function execute( $action, $file = '', $params = '' ) {

                if ( ! empty ( $params ) ) {
                    extract( $params );
                }

                // Setup the filesystem with creds
                require_once( ABSPATH . '/wp-admin/includes/template.php' );
                require_once( ABSPATH . '/wp-admin/includes/file.php' );

                if ( $this->parent->args['menu_type'] == 'submenu' ) {
                    $page_parent = $this->parent->args['page_parent'];
                    $base        = $page_parent . '?page=' . $this->parent->args['page_slug'];
                } else {
                    $base = 'admin.php?page=' . $this->parent->args['page_slug'];
                }

                $url = wp_nonce_url( $base );

                if ( ! isset( $this->creds ) ) {
                    if ( false === ( $this->creds = request_filesystem_credentials( $url, 'direct', false, false ) ) ) {
                        $res = $this->do_action( $action, $file, $params );
                        if ( $res ) {
                            $this->creds = true;
                        } else {
                            return true;
                        }
                    }
                }

                if ( ! WP_Filesystem( $this->creds ) ) {

                    $res = $this->do_action( $action, $file, $params );
                    if ( $res ) {
                        return $res;
                    } else if ( is_admin() ) {
                        // our credentials were no good, ask the user for them again
                        ob_start();
                        request_filesystem_credentials( $url, '', true, false );
                        $this->ftp_form = ob_get_contents();
                        ob_end_clean();

                        return true;
                    } else {
                        return false;
                    }

                }

                return $this->do_action( $action, $file, $params );
            }

            public function do_action( $action, $file = '', $params = '' ) {

                if ( ! empty ( $params ) ) {
                    extract( $params );
                }

                global $wp_filesystem;

                if ( defined( 'FS_CHMOD_FILE' ) ) {
                    $chmod = FS_CHMOD_FILE;
                }

                // Do unique stuff
                if ( $action == 'mkdir' && ! isset( $this->filesystem->killswitch ) ) {
                    wp_mkdir_p( $file );
                   
                    $res = file_exists( $file );
                    if ( defined( 'FS_CHMOD_DIR' ) ) {
                        $chmod = FS_CHMOD_DIR;
                    } else {
                        $chmod = 0755;
                    }
                    if ( ! $res ) {
                        mkdir( $file, $chmod, true );
                        $res = file_exists( $file );
                    }
                } elseif ( $action == 'copy' && ! isset( $this->filesystem->killswitch ) ) {
                    $res = $wp_filesystem->copy( $file, $destination, $overwrite, $chmod );
                    if ( ! $res ) {
                        $res = copy( $file, $destination );
                        if ( $res ) {
                            chmod( $destination, $chmod );
                        }
                    }
                } elseif ( $action == 'put_contents' && ! isset( $this->filesystem->killswitch ) ) {
                    $res = $wp_filesystem->put_contents( $file, $content, $chmod );
                    if ( ! $res ) {
                        $res = file_put_contents( $file, $content );
                        if ( $res ) {
                            chmod( $file, $chmod );
                        }
                    }
                } elseif ( $action == 'get_contents' ) {
                    $res = $wp_filesystem->get_contents( $file );
                    if ( ! $res ) {
                        $res = file_get_contents( $file );
                    }
                } elseif ( $action == 'object' ) {
                    $res = $wp_filesystem;
                }
                if ( isset( $res ) && ! $res ) {
                    $this->killswitch = true;
                }

                return $res;
            }
        }
    }
