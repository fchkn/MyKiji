<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ArticlesFixture
 */
class ArticlesFixture extends TestFixture
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
                'title' => 'Lorem ipsum dolor sit amet',
                'text' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'tag_1' => 'Lorem ipsum dolor sit amet',
                'tag_2' => 'Lorem ipsum dolor sit amet',
                'tag_3' => 'Lorem ipsum dolor sit amet',
                'tag_4' => 'Lorem ipsum dolor sit amet',
                'tag_5' => 'Lorem ipsum dolor sit amet',
                'tag_6' => 'Lorem ipsum dolor sit amet',
                'created' => '2022-11-06 10:18:52',
                'modified' => '2022-11-06 10:18:52',
            ],
        ];
        parent::init();
    }
}
