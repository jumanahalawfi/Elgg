<?php

$content = elgg_list_entities([
    'type' => 'group',
    'relationship' => 'member',
    'relationship_guid' => elgg_get_logged_in_user_guid(),
    'inverse_relationship' => false,
    'sort_by' => [
        'property' => 'name',
        'direction' => 'ASC',
    ],
    'no_results' => elgg_echo('groups:none'),
]);

$sidebar = elgg_view('groups/sidebar/find');


echo elgg_view_page('groups:all', [
    'content' => $content,
    'sidebar' => $sidebar,
    'filter_id' => 'groups/member',
    'filter_value' => 'member',
]);