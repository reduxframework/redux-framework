<?php

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if (!class_exists('reduxNewsflash')) {
        class reduxNewsflash {
            private $parent         = null;
            private $notice_json    = '';

            public function __construct ($parent) {
                $this->parent = $parent;

                $notice_dir         = ReduxFramework::$_upload_dir . 'notice';
                $this->notice_json  = $notice_dir . '/notice.json';

                if (!is_dir ( $notice_dir )) {
                    $parent->filesystem->execute('mkdir', $notice_dir);
                }

                if (!file_exists($this->notice_json)) {
                    $this->get_notice_json();
                } else {
                    $notice_filetime    = filemtime($this->notice_json);
                    $cur_filetime       = time();
                    $three_days         = 60 * 60 * 72;
                    $notice_plus_three  = $notice_filetime + $three_days;

                    if ($cur_filetime >= $notice_plus_three) {
                        $this->get_notice_json();
                    }
                }

                $this->display_message();
            }

            private function get_notice_json() {
                $filesystem = $this->parent->filesystem;

                $data = $filesystem->execute('get_contents', 'http://www.reduxframework.com/wp-content/uploads/redux/redux_notice.json');

                if ($data != '' || !empty($data)) {
                    $params = array(
                        'content' => $data
                    );

                    $filesystem->execute('put_contents', ReduxFramework::$_upload_dir . 'notice/notice.json', $params);
                }
            }

            private function display_message(){
                if (file_exists($this->notice_json)) {
                    $data = $this->parent->filesystem->execute('get_contents', $this->notice_json);
                    $data = (Array)json_decode($data);

                    if (is_array($data) && !empty($data)) {
                        // No message means nothing to display.
                        if (!isset($data['message']) || $data['message'] == '' || empty($data['message'])) {
                            return;
                        }

                        $data['type']   = isset($data['type']) && $data['type'] != '' ? $data['type'] : 'updated';
                        $data['title']  = isset($data['title']) && $data['title'] != '' ? $data['title'] : 'Newsflash from Redux: ';

                        $this->parent->admin_notices[] = array(
                            'type'    => $data['type'],
                            'msg'     => '<strong>' . $data['title'] . '</strong><br/>' . $data['message'],
                            'id'      => 'dev_notice_' . filemtime($this->notice_json),
                            'dismiss' => true,
                        );
                    }
                }
            }
        }
    }