<?php

namespace Elgg\Actions\Admin;

use Elgg\ActionResponseTestCase;
use Elgg\Helpers\Actions\Admin\UpgradeTestBatch;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

/**
 * @group UpgradeService
 * @group UpgradeAction
 * @group Admin
 */
class UpgradeIntegrationTest extends ActionResponseTestCase {

	public function up() {
		parent::up();
		
		_elgg_services()->session_manager->setLoggedInUser($this->getAdmin());
	}
	
	public function testUpgradeFailsWithInvalidUpgradeEntity() {
		$response = $this->executeAction('admin/upgrade', [
			'guid' => -5,
		]);
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
	}

	public function testUpgradeSucceeds() {
		elgg_delete_admin_notice('pending_upgrades');

		$batch = new UpgradeTestBatch();
		$version = $batch->getVersion();

		$upgrade = new \ElggUpgrade();
		$upgrade->setClass(UpgradeTestBatch::class);
		$upgrade->setId("test_plugin:$version");
		$upgrade->title = "test_plugin:upgrade:$version:title";
		$upgrade->description = "test_plugin:upgrade:$version:title";
		$upgrade->access_id = ACCESS_PUBLIC;
		$upgrade->save();

		$this->assertTrue(elgg_admin_notice_exists('pending_upgrades'));

		$response = $this->executeAction('admin/upgrade', [
			'guid' => $upgrade->guid,
		]);

		$this->assertInstanceOf(OkResponse::class, $response);

		$this->assertEquals([
			'errors' => [],
			'numErrors' => 0,
			'numSuccess' => 100,
			'isComplete' => true,
		], $response->getContent());

		$this->assertTrue($upgrade->isCompleted());
		if (!_elgg_services()->upgrades->getPendingUpgrades()) {
			$this->assertFalse(elgg_admin_notice_exists('pending_upgrades'));
		}
	}
}
