<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FavoritesFixture
 */
class FavoritesFixture extends TestFixture
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
                'article_id' => 'Lorem ipsum dolor sit amet',
                'created' => '2022-11-03 13:19:55',
                'modified' => '2022-11-03 13:19:55',
            ],
        ];
        parent::init();
    }
}
