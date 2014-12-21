<?php
if ( ! class_exists('scssc')) {
    include_once dirname(__FILE__) . '/src/Colors.php';
    include_once dirname(__FILE__) . '/src/Compiler.php';
    include_once dirname(__FILE__) . '/src/Formatter.php';
    include_once dirname(__FILE__) . '/src/Formatter/Compact.php';
    include_once dirname(__FILE__) . '/src/Formatter/Compressed.php';
    include_once dirname(__FILE__) . '/src/Formatter/Crunched.php';
    include_once dirname(__FILE__) . '/src/Formatter/Expanded.php';
    include_once dirname(__FILE__) . '/src/Formatter/Nested.php';
    include_once dirname(__FILE__) . '/src/Parser.php';
    include_once dirname(__FILE__) . '/src/Version.php';
    include_once dirname(__FILE__) . '/src/Server.php';
    include_once dirname(__FILE__) . '/classmap.php';
}
