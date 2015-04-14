<?php

    // Added by KP on March 31, 2015.  So, if something is buggered, it's probably my bad!  ;-)

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if (!class_exists('reduxNewsflash')) {
        class reduxNewsflash {
            private $parent         = null;
            private $notice_json    = '';
            private $server_file    = '';
            private $interval       = 3;
            private $cookie_id      = '';

            public function __construct ($parent, $params) {
                // set parent object
                $this->parent = $parent;

                extract($params);
                $this->server_file  = $server_file;
                $this->interval     = isset($interval) ? $interval: 3;
                $this->cookie_id    = isset($cookie_id) ? $cookie_id : $parent->args['opt_name'] . '_blast';
                
                // set notice file location
                $notice_dir         = ReduxFramework::$_upload_dir . $dir_name;// 'notice';
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
                    if ( ! isset( $_COOKIE[$this->cookie_id] ) ) {
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
                $data = $filesystem->execute('get_contents', $this->server_file);// 'http://www.reduxframework.com/' . 'wp-content/uploads/redux/redux_notice.json');

                // if some data exists
                if ($data != '' || !empty($data)) {
                    
                    // if local notice file exists
                    if (file_exists($this->notice_json)) {
                        
                        // get cached data
                        $cache_data = $filesystem->execute('get_contents', $this->notice_json);

                        // if local and server data are same, then return
                        if (  strcmp ( $data, $cache_data ) == 0) {
                            // set new cookie for interval value
                            setcookie( $this->cookie_id, time(), time() + (86400 * $this->interval), '/' );
                            
                            // bail out
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
                    setcookie( $this->cookie_id, time(), time() + (86400 * $this->interval), '/' );
                    
                    // set unique key for dismiss meta key
                    update_option($this->cookie_id, time());
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
                        
                        $data['color']  = isset($data['color']) ? $data['color'] : '#00A2E3';
                        
                        // get unique meta key
                        $key = get_option($this->cookie_id);
                        
                        // set admin notice array
                        $this->parent->admin_notices[] = array(
                            'type'      => $data['type'],
                            'msg'       => $data['title'] . $data['message'],
                            'id'        => $this->cookie_id . '_' . $key,
                            'dismiss'   => true,
                            'color'     => $data['color']
                        );
                    }
                }
            }
        }
    }