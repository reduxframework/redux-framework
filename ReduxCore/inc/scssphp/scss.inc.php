<?php
if ( ! class_exists('scssc')) {
    include_once __DIR__ . '/src/Colors.php';
    include_once __DIR__ . '/src/Compiler.php';
    include_once __DIR__ . '/src/Formatter.php';
    include_once __DIR__ . '/src/Formatter/Compact.php';
    include_once __DIR__ . '/src/Formatter/Compressed.php';
    include_once __DIR__ . '/src/Formatter/Crunched.php';
    include_once __DIR__ . '/src/Formatter/Expanded.php';
    include_once __DIR__ . '/src/Formatter/Nested.php';
    include_once __DIR__ . '/src/Parser.php';
    include_once __DIR__ . '/src/Version.php';
    include_once __DIR__ . '/src/Server.php';
    include_once __DIR__ . '/classmap.php';
}
