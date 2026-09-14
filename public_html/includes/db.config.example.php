<?php
/**
 * Template for includes/db.config.php.
 *
 * Copy this file to db.config.php on the server and fill in the real values:
 *     cp includes/db.config.example.php includes/db.config.php
 *
 * db.config.php is gitignored so credentials never reach the repository.
 * Alternatively, set DB_HOST / DB_NAME / DB_USER / DB_PASS as environment
 * variables — those take precedence over this file.
 */
return [
    'host' => 'localhost',
    'name' => 'your_database_name',
    'user' => 'your_database_user',
    'pass' => 'your_database_password',
];
