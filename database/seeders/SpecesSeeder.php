<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Spaces;

class SpecesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $spaces = [
            'name' => "General",
            'about' => "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text,",
        ];

        Spaces::create($spaces);
    }
}
