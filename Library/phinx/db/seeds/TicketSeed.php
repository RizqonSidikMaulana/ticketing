<?php


use Phinx\Seed\AbstractSeed;

class TicketSeed extends AbstractSeed
{

    public function getDependencies(): array
    {
        return [
            'EventSeed',
        ];
    }
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
                'event_id' => 1,
                'status' => true,
                'ticket_code' => 'DTK19UIR87',
                'created_at' => date('Y-m-d H:i:s'),
            ],[
                'event_id' => 2,
                'status' => false,
                'ticket_code' => 'DTK2019IW8',
                'created_at' => date('Y-m-d H:i:s'),
            ]
        ];

        $posts = $this->table('ticket');
        $posts->insert($data)
              ->saveData();
    }
}
