<?php
final class QAPL_Test_Ajax_Query_Builder {
    public static function run_all(): void {
        error_log('[QAPL TEST]['.__CLASS__ .'] START');
        self::test_normalization();
        self::test_offset();
        self::test_tax_query();
        self::test_tax_exists();
        self::test_generate_tax_query();
        self::test_quick_ajax_id();
        error_log('[QAPL TEST]['.__CLASS__ .'] FINISHED');
    }
    public static function test_normalization(): void {
        //test 1 normalization and defaults
        $builder = new QAPL_Ajax_Query_Builder();
        $suite = QAPL_Test_Assert::suite(__CLASS__, __FUNCTION__);
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => '10',
            'paged'          => '2',
            'post__not_in'   => '5,6,6,foo',
        ];
        $result = $builder->wp_query_args($args, [QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID => '123']);
        $expected = [
            'post_type'      => 'post',
            'posts_per_page' => 10,
            'paged'          => 2,
            'post_status'    => 'publish',
        ];
        foreach ($expected as $key => $value) {
            QAPL_Test_Assert::assert(isset($result[$key]) && $result[$key] === $value, $key . ' correct', $suite, $result[$key] ?? null, $value);
        }
        QAPL_Test_Assert::assert($result['post__not_in'] === [5, 6], 'post__not_in sanitized', $suite, $result['post__not_in'], [5, 6]);
    }

    public static function test_offset(): void {
        //test 2 offset overrides paged
        $builder = new QAPL_Ajax_Query_Builder();
        $suite = QAPL_Test_Assert::suite(__CLASS__, __FUNCTION__);
        $args = [
            'post_type' => 'post',
            'paged'     => 3,
            'offset'    => 20,
        ];
        $result = $builder->wp_query_args($args, []);
        QAPL_Test_Assert::assert(isset($result['offset']), 'offset exists', $suite);
        QAPL_Test_Assert::assert(!isset($result['paged']), 'paged removed when offset used', $suite);
        QAPL_Test_Assert::assert($result['offset'] === 20, 'offset value correct', $suite, $result['offset'], 20);
    }

     public static function test_tax_query(): void {
        // test 3 tax_query with terms
        $builder = new QAPL_Ajax_Query_Builder();
        $suite = QAPL_Test_Assert::suite(__CLASS__, __FUNCTION__);
        $args = [
            'post_type'         => 'post',
            'selected_taxonomy' => 'category',
            'selected_terms'    => '1,2,2,0,abc',
        ];
        $result = $builder->wp_query_args($args, []);
        QAPL_Test_Assert::assert(isset($result['tax_query']), 'tax_query exists', $suite);
        QAPL_Test_Assert::assert($result['tax_query'][0]['taxonomy'] === 'category', 'taxonomy set', $suite, $result['tax_query'][0]['taxonomy'], 'category');
        QAPL_Test_Assert::assert($result['tax_query'][0]['terms'] === [1, 2], 'terms sanitized', $suite, $result['tax_query'][0]['terms'], [1, 2]);
     }

     public static function test_tax_exists(): void {
        //test 4 tax_query exists operator
        $builder = new QAPL_Ajax_Query_Builder();
        $suite = QAPL_Test_Assert::suite(__CLASS__, __FUNCTION__);
        $args = [
            'post_type'         => 'post',
            'selected_taxonomy' => 'category',
        ];
        $result = $builder->wp_query_args($args, []);
        QAPL_Test_Assert::assert($result['tax_query'][0]['operator'] === 'EXISTS', 'taxonomy exists operator', $suite, $result['tax_query'][0]['operator'], 'EXISTS');

     }

     public static function test_generate_tax_query(): void {
        //test 5 generate_tax_query
        $builder = new QAPL_Ajax_Query_Builder();
        $suite = QAPL_Test_Assert::suite(__CLASS__, __FUNCTION__);
        $base_args = [
            'post_type' => 'post',
            'paged'     => 2,
        ];
        $result = $builder->generate_tax_query($base_args, 'category', 5);
        QAPL_Test_Assert::assert(!isset($result['paged']), 'paged removed in generate_tax_query', $suite);
        QAPL_Test_Assert::assert($result['tax_query'][0]['terms'] === 5, 'single term set', $suite, $result['tax_query'][0]['terms'], 5);
     }

     public static function test_quick_ajax_id(): void {
        //test 6 quick_ajax_id generation
        $builder = new QAPL_Ajax_Query_Builder();
        $suite = QAPL_Test_Assert::suite(__CLASS__, __FUNCTION__);
        $builder->wp_query_args([], [QAPL_Constants::ATTRIBUTE_QUICK_AJAX_ID => '123']);
        $id = $builder->get_quick_ajax_id();
        //strpos('c123', 'c');   : 0
        //strpos('abc123', 'c'); : 2
        //strpos('123', 'c');    : false
        QAPL_Test_Assert::assert(strpos($id, 'c') === 0, 'quick_ajax_id has prefix', $suite, $id, 'c123');
     }
}
QAPL_Test_Ajax_Query_Builder::run_all();