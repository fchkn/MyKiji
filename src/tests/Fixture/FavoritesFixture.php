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
                'article_id' => 1,
                'created' => '2022-11-09 15:41:30',
                'modified' => '2022-11-09 15:41:30',
            ],
        ];
        parent::init();
    }
}
