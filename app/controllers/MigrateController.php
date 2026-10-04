<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/**
 * TEMPORARY / ONE-TIME USE ONLY.
 *
 * LavaLust ships without a working CLI "migrate" command, so this
 * controller lets you run pending migrations (e.g. the products table)
 * over HTTP by visiting /run-migrations once, locally or on Render.
 *
 * SECURITY: after you've created the products table, set
 * $config['migration_enabled'] back to FALSE in app/config/migration.php
 * (or delete this controller and its route) so this endpoint can't be
 * hit again in production.
 */
class MigrateController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->call->library('migration');
    }

    public function run()
    {
        header('Content-Type: text/plain');

        ob_start();
        $this->migration->migrate();
        $output = ob_get_clean();

        echo $output !== '' ? $output : "Migration command ran with no output.\n";
        echo "\nDone. Remember to set migration_enabled back to FALSE in app/config/migration.php.\n";
    }
}
