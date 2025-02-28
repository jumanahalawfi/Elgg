<?php

namespace Elgg\Actions;

use Elgg\ActionResponseTestCase;
use Elgg\Exceptions\Http\GatekeeperException;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;
use Elgg\Values;

/**
 * @group ActionsService
 * @group AccountActions
 * @group Logout
 */
class LogoutIntegrationTest extends ActionResponseTestCase {

	public function up() {
		parent::up();

		self::createApplication(['isolate' => true]);
	}

	public function testLogoutFailsWithoutActiveSession() {
		$this->expectException(GatekeeperException::class);
		$this->executeAction('logout');
	}

	public function testLogout() {

		$user = $this->createUser([
			'password' => 123456,
			'language' => 'de',
		]);

		elgg_login($user);

		$response = $this->executeAction('logout');

		$this->assertInstanceOf(OkResponse::class, $response);

		$messages = _elgg_services()->system_messages->dumpRegister();
		$this->assertNotEmpty($messages['success']);
		$this->assertEquals(elgg_echo('logoutok', [], $user->language), array_shift($messages['success']));

		$this->assertNull(_elgg_services()->session_manager->getLoggedInUser());
	}

	public function testCanUseLogoutActionWithoutTokens() {
		$user = $this->createUser([
			'password' => 123456,
			'language' => 'de',
		]);

		elgg_login($user);

		$response = $this->executeAction('logout', [], false, false);

		$this->assertInstanceOf(OkResponse::class, $response);

		$messages = _elgg_services()->system_messages->dumpRegister();
		$this->assertNotEmpty($messages['success']);
		$this->assertEquals(elgg_echo('logoutok', [], $user->language), array_shift($messages['success']));

		$this->assertNull(_elgg_services()->session_manager->getLoggedInUser());
	}

	public function testCanPreventLogoutWithEvent() {

		$user = $this->createUser([
			'password' => 123456,
			'language' => 'de',
		]);

		$user->setValidationStatus(true);

		elgg_login($user);

		elgg_register_event_handler('logout:before', 'user', [Values::class, 'getFalse']);

		$response = $this->executeAction('logout');

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('logouterror'), $response->getContent());

		$this->assertEquals($user, _elgg_services()->session_manager->getLoggedInUser());

		_elgg_services()->session_manager->removeLoggedInUser();

		elgg_unregister_event_handler('logout:before', 'user', [Values::class, 'getFalse']);
	}
}
