<?php
elgg_require_js('resources/bin/bin');

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

$checkbox = elgg_view('input/checkbox', [
    'name' => 'restore-checkbox',
    'id' => 'restore-checkbox',
    'value' => 1,
    'checked' => false,
    'label' => elgg_echo('Restore Recursively'),
]);

echo elgg_view_page(
    elgg_echo('collection:object:bin'),
    elgg_view_layout('admin', [
        'title' => elgg_echo('collection:object:bin'),
        'content' => $checkbox . $content,
        'filter_id' => 'admin',
    ])
);

