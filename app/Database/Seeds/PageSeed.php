<?php

namespace App\Database\Seeds;

use App\Models\Page;
use CodeIgniter\Database\Seeder;

class PageSeed extends Seeder
{
    public function run()
    {
        $seed = new Page();

        $data = [
            "slug" => str_replace(" ", "-", trim("Lorem Ipsum")),
            "title" => "Lorem Ipsum",
            "content" => "Lorem Ipsum content "
        ];

        $seed->insert($data);
    }
}
