<?php

namespace Database\Seeders;

use App\Models\AcquaintedUs;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AcquaintedUsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'title' => [
                    'en' => 'Google Ads',
                    'fa' => 'گوگل اد',
                ],
            ],
            [
                'title' => [
                    'en' => 'Advertises',
                    'fa' => 'تبلیغات',
                ],
            ],
            [
                'title' => [
                    'en' => 'Freinds',
                    'fa' => 'معرفی دوستان',
                ],
            ],
            [
                'title' => [
                    'en' => 'Google',
                    'fa' => 'گوگل ',
                ],
            ],
            [
                'title' => [
                    'en' => 'Instagram',
                    'fa' => 'اینستاگرام ',
                ],
            ],
        ];

        foreach ($items as $item) {
            $aq = AcquaintedUs::create([
                'title' => $item['title']['fa'],
            ]);
        }
    }
}
