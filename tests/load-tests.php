<?php

if (!defined('ABSPATH')) {
    exit;
}
if (!defined('WP_DEBUG') || !WP_DEBUG) {
    return;
}
if (!isset($_GET['qapl_run_tests'])) {
    return;
}

final class QAPL_Test_Assert {
    public static function suite(string $class_name, string $test_name): string {
        return $class_name . '::' . $test_name;
    }
    public static function log(string $status, string $message, string $suite): void {
        error_log('[QAPL TEST][' . $suite . '] ' . $message . ' ' . $status);
    }
    public static function assert( bool $condition, string $message, string $suite, $actual = null, $expected = null): void {
        if ($condition) {
            self::log('[OK]', $message, $suite);
            return;
        }
        if ($expected !== null || $actual !== null) {
            $message .= ' (expected → ' . print_r($expected, true);
            $message .= ', actual → ' . print_r($actual, true) . ')';
        }
        self::log('[FAIL]', $message, $suite);
    }
}


$test_file = __DIR__ . '/ajax/class-test-ajax-query-builder.php';
if (file_exists($test_file)) {
    require_once $test_file;
}