<?php

/*
$active_group_list_params = elgg_list_entities_from_relationship_count([
    'owner_guid' => elgg_get_logged_in_user_guid(),
    'type' => 'group',
    'relationship' => 'member',
    'inverse_relationship' => false,
    'no_results' => true
]);

echo elgg_view('input/select', array(
    'required' => true,
    'name' => 'status',
    'options_values' => [
        'draft' => elgg_echo('status:draft'),
        'published' => elgg_echo('status:published'),
    ],
));
*/


/**
 * Copied from reportedcontent. Just to test to see if we can get this to pop up
 */

$title = get_input('title', '');
$address = get_input('address', '');
$entity_guid = (int)get_input('entity_guid');

$description = '';

$fields = [
    [
        '#type' => 'text',
        '#label' => elgg_echo('title'),
        'name' => 'title',
        'value' => $title,
        'required' => true,
    ],
    [
        '#type' => 'url',
        '#label' => elgg_echo('reportedcontent:address'),
        'name' => 'address',
        'value' => $address,
        'readonly' => (bool)$address,
        'required' => true,
    ],
    [
        '#type' => 'plaintext',
        '#label' => elgg_echo('reportedcontent:description'),
        'name' => 'description',
        'value' => $description,
    ],
    [
        '#type' => 'hidden',
        'name' => 'entity_guid',
        'value' => $entity_guid,
    ],
];

foreach ($fields as $field) {
    echo elgg_view_field($field);
}

$footer = elgg_view('input/submit', [
    'value' => elgg_echo('reportedcontent:report'),
]);
$footer .= elgg_view('input/button', [
    'class' => 'elgg-button-cancel',
    'value' => elgg_echo('cancel'),
]);

elgg_set_form_footer($footer);
