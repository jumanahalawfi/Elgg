<?php

namespace Elgg\Bin;

use Elgg\Plugins\PluginTesting;

class ElggBinIntegrationTest extends \Elgg\IntegrationTestCase {
    use PluginTesting;

    private \ElggUser $admin;
    private \ElggUser $user;
    private \ElggGroup $group_by_admin;
    private \ElggGroup $group_by_user;
    private \ElggBlog $blog_post_1; // owned by admin, soft deleted by admin
    private \ElggBlog $blog_post_2; // owned by user, soft deleted by admin
    private \ElggBlog $blog_post_3; // owned by user, soft deleted by user

    public function up()
    {
        $this->admin = $this->createUser();
        $this->user = $this->createUser();
        $this->group_by_admin = $this->createGroup();
        //$this->group_by_admin->owner_guid =
    }

    public function testCanLoadBinAsUser() {


    }

}