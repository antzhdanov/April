April - PHP Benchmark
---------------------
April is a CLI tool to create benchmark snippets for PHP. Say, you need to
traverse the array and you want to compare the performance of array_map
and foreach for that. Using April such benchmarking becomes a lot easier -
you just ask April to generate the test, fill it with appropriate code
and let it do the rest.

Usage
-----
1. Generate a sceleton test file:
    ```
    $ april new array_map_vs_foreach
    ```
    This will generate the new test file (array_map_vs_foreach.php) under the **cases/** directory. Each test file has at least 2 sections marked with PHP annotation.
2. Add your testing data:
    ``` php
    <?php
    
    /**
     * @init ALL
     */
    $src = array();
    
    foreach ($i = 0; $i < 10000; $i++) {
        $src[] = mt_rand();
    }
    
    $result = array();
    
    /**
     * @end
     */
    
    /**
     * @test array_map
     */
    $result = array_map(function($item) { return $item * 2; }, $src);
    
    /**
     * @end
     */
    
    /**
     * @test foreach
     */
    foreach ($src as $item) {
        $result[] = $item * 2;
    }
    
    /**
     * @end
     */
    ```
3. When test file is created, run:
    ```
    $ april run array_map_vs_foreach
    ```
    April will parse the test file, create two test scripts, execute them and display the statistics.


Annotations
-----------
# @test TEST_NAME
A placeholder for the actual code being tested.

# @init TEST_NAME | ALL
The code placed here will be executed to set up the test before actual measurement.
Depending on name passed to it (TEST_NAME or ALL) the code will be executed before
TEST_NAME or all tests in a case.

# @end
Takes no arguments - marks the end of section.
