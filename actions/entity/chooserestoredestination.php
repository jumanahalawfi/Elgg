<?php
/**
 * Action for choosing destination to restore a post to. For now, just redirect to the stub page for selecting target group
 *
 * Try 1: Redirect to temporary bin
 * Try 2: Redirect to target group selection stub
 *
 *
 */

// TODO: Inside the action, only sets the relationship changes (see reportedcontent for example). The below code is bullshit for now.

// Does not do anything yet, only redirects
$site_url = elgg_get_site_url();
$forward_url = $site_url . 'bin';
$message = 'Test redirecting worked, go to temporary bin';

$guid = (int) get_input('guid');

/**
// Spits out the form to choose groups
elgg_register_ajax_view('forms/entity/chooserestoredestination');

**/

echo elgg_view_form('chooserestoredestination', ['ajax' => true],
    ['guid' => $guid]);

//return elgg_ok_response('', $message, $forward_url);
