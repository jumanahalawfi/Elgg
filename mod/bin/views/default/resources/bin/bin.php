<?php

$list_params = [
    'relationship' => 'deleted_by',
    'type_subtype_pairs' => elgg_entity_types_with_capability('soft_deletable'),
    'inverse_relationship' => false,
    'no_results' => true
];

if (!elgg_is_admin_logged_in()) {
    $list_params['owner_guid'] = elgg_get_logged_in_user_guid();
}

$content = elgg_call(ELGG_SHOW_SOFT_DELETED_ENTITIES, function () use ($list_params) {
    return elgg_list_entities($list_params);
});

// check-box
$toggle_button = elgg_view('input/checkbox', [
    'name' => 'recursive-toggle',
    'id' => 'recursive-toggle',
    'checked' => false,
]);

$toggle_label = elgg_format_element('label', [
    'for' => 'recursive-toggle',
    'class' => 'elgg-toggle-label',
], elgg_echo('Restore recursively'));

$toggle_div = elgg_format_element('div', ['class' => 'elgg-toggle elgg-bin-toggle'], $toggle_button . $toggle_label);

echo elgg_view_page(
    elgg_echo('collection:object:bin'),
    elgg_view_layout('admin', [
        'title' => elgg_echo('collection:object:bin'),
        'content' => elgg_format_element('div', ['class' => 'elgg-bin-toggle'], $toggle_div) . $content,
        'filter_id' => 'admin',
    ])
);

