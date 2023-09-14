<?php


use Phinx\Seed\AbstractSeed;

class EventSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [
            [
                'id' => 1,
                'event_name' => 'Pesta Rakyat',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'id' => 2,
                'event_name' => 'Kemerdekaan',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $posts = $this->table('event');
        $posts->insert($data)
              ->saveData();
    }
}
