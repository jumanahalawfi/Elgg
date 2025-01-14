<?php

namespace Elgg\Cli;

use Elgg\IntegrationTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 * @group Plugins
 */
class PluginsCommandIntegrationTest extends IntegrationTestCase {

	public function up() {
		$this->createApplication([
			'isolate' => true,
			'plugins_path' => $this->normalizeTestFilePath('mod/'),
		]);

		_elgg_services()->logger->disable();

		$ids = [
			'parent_plugin',
			'dependent_plugin',
			'conflicting_plugin',
			'static_config',
		];

		foreach ($ids as $id) {
			$plugin = \ElggPlugin::fromId($id, $this->normalizeTestFilePath('mod/'));
			$plugin->deactivate();
		}
	}

	public function down() {
		$ids = [
			'parent_plugin',
			'dependent_plugin',
			'conflicting_plugin',
			'static_config',
		];

		foreach ($ids as $id) {
			elgg_call(ELGG_IGNORE_ACCESS, function() use ($id) {
				elgg_get_plugin_from_id($id)->delete();
			});
		}
	}

	public function testActivatesPluginsWithDependencies() {
		$application = new Application();

		$this->assertFalse(elgg_is_active_plugin('parent_plugin'));
		$this->assertFalse(elgg_is_active_plugin('dependent_plugin'));
		
		$command = new PluginsActivateCommand();
		$application->add($command);

		$command = $application->find('plugins:activate');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--force' => true,
			'plugins' => ['dependent_plugin'],
		]);

		$this->assertEquals(0, $commandTester->getStatusCode());

		$this->assertTrue(elgg_is_active_plugin('parent_plugin'));
		$this->assertTrue(elgg_is_active_plugin('dependent_plugin'));
	}

	public function testActivatesPluginsWithOrder() {
		$application = new Application();
		
		$static = elgg_get_plugin_from_id('static_config');
		
		$static->deactivate();
		$static->setPriority('first');
		$current_priority = $static->getPriority();
		
		$parent = elgg_get_plugin_from_id('parent_plugin');
		
		$this->assertLessThan($parent->getPriority(), $static->getPriority());
		
		$this->assertFalse(elgg_is_active_plugin('parent_plugin'));
		$this->assertFalse(elgg_is_active_plugin('static_config'));
		
		$command = new PluginsActivateCommand();
		$application->add($command);
		
		$command = $application->find('plugins:activate');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--force' => true,
			'plugins' => ['static_config:last', 'parent_plugin'],
		]);
		
		$this->assertEquals(0, $commandTester->getStatusCode());
		
		$this->assertTrue(elgg_is_active_plugin('parent_plugin'));
		$this->assertTrue(elgg_is_active_plugin('static_config'));
		
		$static = elgg_get_plugin_from_id('static_config');
		$parent = elgg_get_plugin_from_id('parent_plugin');
		
		$this->assertNotEquals($current_priority, $static->getPriority());
		$this->assertGreaterThan($parent->getPriority(), $static->getPriority());
	}

	public function testDeactivatesConflictingPlugins() {
		elgg_get_plugin_from_id('parent_plugin')->activate();

		$application = new Application();
		
		$this->assertTrue(elgg_is_active_plugin('parent_plugin'));
		$this->assertFalse(elgg_is_active_plugin('conflicting_plugin'));

		$command = new PluginsActivateCommand();
		$application->add($command);

		$command = $application->find('plugins:activate');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--force' => true,
			'plugins' => ['conflicting_plugin'],
		]);

		$this->assertEquals(0, $commandTester->getStatusCode());

		$this->assertFalse(elgg_is_active_plugin('parent_plugin'));
		$this->assertTrue(elgg_is_active_plugin('conflicting_plugin'));
	}

	public function testDeactivatesDependentPlugins() {
		elgg_get_plugin_from_id('parent_plugin')->activate();
		elgg_get_plugin_from_id('dependent_plugin')->activate();

		$application = new Application();

		$command = new PluginsDeactivateCommand();
		$application->add($command);

		$command = $application->find('plugins:deactivate');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--force' => true,
			'plugins' => ['parent_plugin'],
		]);

		$this->assertEquals(0, $commandTester->getStatusCode());

		$this->assertFalse(elgg_is_active_plugin('parent_plugin'));
		$this->assertFalse(elgg_is_active_plugin('dependent_plugin'));
	}
}