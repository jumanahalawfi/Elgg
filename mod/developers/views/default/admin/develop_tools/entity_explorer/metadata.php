<?php

$entity = elgg_extract('entity', $vars);

$entity_metadata = elgg_get_metadata(['guid' => $entity->guid, 'limit' => false]);

if (empty($entity_metadata)) {
	$metadata_info = elgg_echo('notfound');
} else {
	$md_columns = ['id', 'name', 'value', 'value_type', 'time_created', 'enabled'];
	
	$metadata_info = '<table class="elgg-table">';
	$metadata_info .= '<thead><tr>';
	foreach ($md_columns as $md_col) {
		$metadata_info .= elgg_format_element('th', [], $md_col);
	}
	
	$metadata_info .= '<th>&nbsp;</th>';
	$metadata_info .= '</tr></thead>';
	$metadata_info .= '<tbody>';
	
	foreach ($entity_metadata as $md) {
		$metadata_info .= '<tr>';
		foreach ($md_columns as $md_col) {
			switch ($md_col) {
				case 'value':
					if (is_bool($md->$md_col)) {
						$value = elgg_view('output/text', ['value' => $md->$md_col ? 'true' : 'false']);
						break;
					}
					
					// other values can be shown as default
				
				default:
					$value = elgg_view('output/text', ['value' => $md->$md_col]);
					break;
			}
			
			$metadata_info .= elgg_format_element('td', [], $value);
		}
		
		$metadata_info .= elgg_format_element('td', [], elgg_view('output/url', [
			'text' => elgg_view_icon('remove'),
			'href' => elgg_http_add_url_query_elements('action/developers/entity_explorer_delete', [
				'guid' => $entity->guid,
				'type' => 'metadata',
				'key' => $md->name,
			]),
			'confirm' => true,
		]));
		$metadata_info .= '</tr>';
	}
	
	$metadata_info .= '</tbody>';
	$metadata_info .= '</table>';
}

echo elgg_view_module('info', elgg_echo('developers:entity_explorer:info:metadata'), $metadata_info);
