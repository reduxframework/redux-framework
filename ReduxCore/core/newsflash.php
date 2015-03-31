<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if (!class_exists('reduxNewsflash')) {
        class reduxNewsflash {
            private $parent         = null;
            private $notice_json    = '';

            public function __construct ($parent) {
                // set parent object
                $this->parent = $parent;

                // set notice file location
                $notice_dir         = ReduxFramework::$_upload_dir . 'notice';
                $this->notice_json  = $notice_dir . '/notice.json';

                // verify notice dir exists
                if (!is_dir ( $notice_dir )) {
                    
                    // create notice dir
                    $parent->filesystem->execute('mkdir', $notice_dir);
                }

                // if notice file does not exists
                if (!file_exists($this->notice_json)) {

                    // get notice data from server and create cache file
                    $this->get_notice_json();
                } else {
                    
                    // check expiry time
                    if ( ! isset( $_COOKIE['redux_notice_check'] ) ) {
                        
                        // expired!  get notice data from server
                        $this->get_notice_json();
                    }
                }

                // set the admin notice msg
                $this->display_message();
            }

            private function get_notice_json() {

                // filesystem object
                $filesystem = $this->parent->filesystem;
                
                // get notice data from server
                $data = $filesystem->execute('get_contents', 'http://www.reduxframework.com/' . 'wp-content/uploads/redux/redux_notice.json');

                // if some data exists
                if ($data != '' || !empty($data)) {
                    
                    // if local notice file exists
                    if (file_exists($this->notice_json)) {
                        
                        // get cached data
                        $cache_data = $filesystem->execute('get_contents', $this->notice_json);

                        // if local and server data are same, then return
                        if (  strcmp ( $data, $cache_data ) == 0) {
                            return;
                        }
                    }
                
                    // set server data
                    $params = array(
                        'content' => $data
                    );

                    // write local notice file with new data
                    $filesystem->execute('put_contents', $this->notice_json, $params);
                    
                    // set cookie for three day expiry
                    setcookie( "redux_notice_check", 1, time() + (86400 * 3), '/' );
                }
            }

            private function display_message(){
                // notice file exists?
                if (file_exists($this->notice_json)) {
                    // get cached data
                    $data = $this->parent->filesystem->execute('get_contents', $this->notice_json);
                    
                    // decode json string
                    $data = (Array)json_decode($data);

                    // must be array and not empty
                    if (is_array($data) && !empty($data)) {
                        
                        // No message means nothing to display.
                        if (!isset($data['message']) || $data['message'] == '' || empty($data['message'])) {
                            return;
                        }

                        // validate data
                        $data['type']   = isset($data['type']) && $data['type'] != '' ? $data['type'] : 'updated';
                        $data['title']  = isset($data['title']) && $data['title'] != '' ? $data['title'] : '';

                        if ($data['type'] == 'redux-message') {
                            $data['type'] = 'updated redux-message';
                        }
                        
                        // set admin notice array
                        $this->parent->admin_notices[] = array(
                            'type'    => $data['type'],
                            'msg'     => $data['title'] . $data['message'],
                            'id'      => 'dev_notice_' . filemtime($this->notice_json),
                            'dismiss' => true,
                        );
                    }
                }
            }
        }
    }