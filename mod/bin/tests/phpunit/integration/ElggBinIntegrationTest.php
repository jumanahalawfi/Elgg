<?php

namespace Elgg\Bin;

use Elgg\Plugins\PluginTesting;

class ElggBinIntegrationTest extends \Elgg\IntegrationTestCase {
    use PluginTesting;

    private \ElggUser $admin;
    private \ElggUser $user;
    private \ElggGroup $group_by_admin;
    private \ElggGroup $group_by_user;
    private \ElggObject $blog_post_1; // owned by admin, soft deleted by admin
    private \ElggObject $blog_post_2; // owned by user, soft deleted by admin
    private \ElggObject $blog_post_3; // owned by user, soft deleted by user

    public function up()
    {
        $this->admin = $this->createUser();
        $this->admin->makeAdmin();
        $this->user = $this->createUser();

        $this->group_by_admin = $this->createGroup();
        $this->group_by_admin->owner_guid = $this->admin->guid;
        $this->group_by_admin->container_guid = $this->admin->guid;

        $this->group_by_user = $this->createGroup();
        $this->group_by_user->owner_guid = $this->user->guid;
        $this->group_by_user->container_guid = $this->user->guid;

        $this->blog_post_1 = $this->createObject([
            'subtype' => 'blog',
            'comments_on' => 'On',
            'status' => 'published',
            'owner_guid' => $this->admin->guid,
            'container_guid' => $this->group_by_admin->guid,
        ]);

        $this->blog_post_2 = $this->createObject([
            'subtype' => 'blog',
            'comments_on' => 'On',
            'status' => 'published',
            'owner_guid' => $this->user->guid,
            'container_guid' => $this->group_by_user->guid,
        ]);

        $this->blog_post_3 = $this->createObject([
            'subtype' => 'blog',
            'comments_on' => 'On',
            'status' => 'published',
            'owner_guid' => $this->user->guid,
            'container_guid' => $this->group_by_user->guid,
        ]);

    }

    public function testCanLoadBinAsUser() {
        _elgg_services()->session_manager->setLoggedInUser($this->user);

        // nothing has been soft deleted yet
        $this->assertEquals(elgg_get_entities([
            'owner_guid' => $this->user->guid,
            'relationship' => 'deleted_by',
            'type_subtype_pairs' => elgg_entity_types_with_capability('soft_deletable'),
            'inverse_relationship' => false,
            'no_results' => true
        ]), []);

        // should only be able to see blog 3?
        $this->blog_post_2->softDelete($this->admin->guid);
        $this->blog_post_3->softDelete($this->user->guid);
        $this->assertEquals(elgg_get_entities([
            'owner_guid' => $this->user->guid,
            'relationship' => 'deleted_by',
            'type_subtype_pairs' => elgg_entity_types_with_capability('soft_deletable'),
            'inverse_relationship' => false,
            'no_results' => true
        ]), [$this->blog_post_3]);

    }

}