<?php
/**
 * Restore and move action. For now, just redirect to the stub page for selecting target group
 *
 * Try 1: Redirect to temporary bin
 * Try 2: Redirect to target group selection stub
 */

// Does not do anything yet, only redirects
$site_url = elgg_get_site_url();
$forward_url = $site_url;
$message = 'Test redirecting worked, go home';

return elgg_ok_response('', $message, $forward_url);
