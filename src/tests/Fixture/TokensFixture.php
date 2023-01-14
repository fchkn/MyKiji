<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * TokensFixture
 */
class TokensFixture extends TestFixture
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
                'token' => 'Lorem ipsum dolor sit amet',
                'limit_time' => 1,
                'created' => '2022-11-17 07:58:58',
                'modified' => '2022-11-17 07:58:58',
            ],
        ];
        parent::init();
    }
}
