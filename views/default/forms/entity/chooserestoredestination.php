<?php

/**
 * Body of the form for choosing restore destination (currently, of a post).
 * For now just stubs from reportedcontent/add.
 */

elgg_gatekeeper();

$title = '';
$address = '';
$guid = (int) get_input('guid');

$description = '';

$fields = [
    [
        '#type' => 'text',
        '#label' => 'titleLabel',
        'name' => 'title',
        'value' => $title,
        'required' => true,
    ],
    [
        '#type' => 'url',
        '#label' => 'addressLabel',
        'name' => 'address',
        'value' => $address,
        'readonly' => (bool) $address,
        'required' => true,
    ],
    [
        '#type' => 'plaintext',
        '#label' => 'descriptionLabel',
        'name' => 'description',
        'value' => $description,
    ],
    [
        '#type' => 'hidden',
        'name' => 'entity_guid',
        'value' => $guid,
    ],
];

foreach ($fields as $field) {
    echo elgg_view_field($field);
}


$footer = elgg_view('input/submit', [
    'value' => 'send',
]);
$footer .= elgg_view('input/button', [
    'class' => 'elgg-button-cancel',
    'value' => 'cancel',
]);

elgg_set_form_footer($footer);
