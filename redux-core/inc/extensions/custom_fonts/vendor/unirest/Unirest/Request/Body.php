<?php

namespace Unirest\Request;

use Unirest\Request as Request;
use Unirest\Exception as Exception;

class Body
{
    /**
     * Prepares a file for upload. To be used inside the parameters declaration for a request.
     * @param string $filename The file path
     * @param string $mimetype MIME type
     * @param string $postname the file name
     * @return string|\CURLFile
     */
    public static function File($filename, $mimetype = '', $postname = '')
    {
        if (class_exists('CURLFile')) {
            return new \CURLFile($filename, $mimetype, $postname);
        }

        if (function_exists('curl_file_create')) {
            return curl_file_create($filename, $mimetype, $postname);
        }

        return sprintf('@%s;filename=%s;type=%s', $filename, $postname ?: basename($filename), $mimetype);
    }

    public static function Json($data)
    {
        if (!function_exists('json_encode')) {
            throw new Exception('JSON Extension not available');
        }

        return json_encode($data);
    }

    public static function Form($data)
    {
        if (is_array($data) || is_object($data) || $data instanceof \Traversable) {
            return http_build_query(Request::buildHTTPCurlQuery($data));
        }

        return $data;
    }

    public static function Multipart($data, $files = false)
    {
        if (is_object($data)) {
            return get_object_vars($data);
        }

        if (!is_array($data)) {
            return array($data);
        }

        if ($files !== false) {
            foreach ($files as $name => $file) {
                $data[$name] = call_user_func(array(__CLASS__, 'File'), $file);
            }
        }

        return $data;
    }
}
