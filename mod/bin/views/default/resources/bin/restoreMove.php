<?php

$entity_guid = get_input('entity_guid');
$entity = elgg_call(ELGG_SHOW_SOFT_DELETED_ENTITIES, function () use ($entity_guid){
	return get_entity($entity_guid);
});

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



echo elgg_view_page('choose a group from the list', [
	'content' => $content,
	'filter_id' => 'groups/member',
	'filter_value' => 'member',
]);
