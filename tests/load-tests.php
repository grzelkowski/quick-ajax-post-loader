<?php

if (!defined('ABSPATH')) {
    exit;
}
if (!defined('WP_DEBUG') || !WP_DEBUG) {
    return;
}
// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- debug-only test runner gated by WP_DEBUG
if (!isset($_GET['qapl_run_tests'])) {
    return;
}

final class QAPL_Test_Assert {
    public static function suite(string $class_name, string $test_name): string {
        return $class_name . '::' . $test_name;
    }
    public static function log(string $status, string $message, string $suite): void {
        // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- test runner logging
        error_log('[QAPL TEST][' . $suite . '] ' . $message . ' ' . $status);
    }
    public static function assert( bool $condition, string $message, string $suite, $actual = null, $expected = null): void {
        if ($condition) {
            self::log('[OK]', $message, $suite);
            return;
        }
        if ($expected !== null || $actual !== null) {
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r -- test diff output
            $message .= ' (expected → ' . print_r($expected, true);
            // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r -- test diff output
            $message .= ', actual → ' . print_r($actual, true) . ')';
        }
        self::log('[FAIL]', $message, $suite);
    }
}


$qapl_test_file = __DIR__ . '/ajax/class-test-ajax-query-builder.php';
if (file_exists($qapl_test_file)) {
    require_once $qapl_test_file;
}