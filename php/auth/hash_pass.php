<?php
/**
 * CLI helper to generate password_hash() strings.
 * Usage: php hash_pass.php "YourPassword"
 */
if ($argc < 2) {
    fwrite(STDERR, "Usage: php hash_pass.php "YourPassword"\n");
    exit(1);
}
$pwd = $argv[1];
$hash = password_hash($pwd, PASSWORD_DEFAULT);
echo $hash, PHP_EOL;
