<?php

/**
 * This script demonstrates what is a `Tick` in PHP.
 * Most of the statements cause a Tick, for example:
 * <code>
 * $a = 1;
 * $b = 2;
 * </code>
 * The code block above causes 2 Ticks.
 *
 * <code>
 * while ($i < 5) {
 *   $i++;
 * }
 * </code>
 * The code block above causes 5 + 1 Ticks. The end of a while-loop is counted as another Tick.
 *
 * <code>
 * declare(ticks=1);
 * </code>
 * The code block above defines how frequent the `tick_function` should be triggered.
 * Tick function can be registered using `register_tick_function` function.
 */

register_tick_function(function(): void { echo "x";});

// Tick function will be triggered for every tick.
declare(ticks=1);
$i = 0;
while ($i < 5) {
    echo $i;
    $i++;
}
echo PHP_EOL;

// Tick function will be triggered for every 2 ticks.
declare(ticks=2);
$i = 0;
while ($i < 5) {
    echo $i;
    $i++;
}
echo PHP_EOL;