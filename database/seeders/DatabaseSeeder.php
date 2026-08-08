<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PermissionsTableSeeder::class,
            RolesTableSeeder::class,
            PermissionRoleTableSeeder::class,
            UsersTableSeeder::class,
            RoleUserTableSeeder::class,
            EventsTableSeeder::class,
            PostsTableSeeder::class,
            SettingsTableSeeder::class,
            SpeakersTableSeeder::class,
            SchedulesTableSeeder::class,
            VenuesTableSeeder::class,
            HotelsTableSeeder::class,
            GalleriesTableSeeder::class,
            SponsorsTableSeeder::class,
            FaqsTableSeeder::class,
            AmenitiesTableSeeder::class,
            PricesTableSeeder::class,
            AmenityPriceTableSeeder::class,
            SampleEventTwoAndContactsSeeder::class,
            CompanyProfileContentSeeder::class,
            MenusTableSeeder::class,
        ]);
    }
}
