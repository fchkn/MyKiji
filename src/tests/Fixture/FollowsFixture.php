<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FollowsFixture
 */
class FollowsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'follow_user_id' => 1,
                'created' => '2022-11-09 23:36:37',
                'modified' => '2022-11-09 23:36:37',
            ],
        ];
        parent::init();
    }
}
