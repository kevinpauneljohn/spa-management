<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class ActivityLogsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = ['view activity','add activity','edit activity','delete activity'];
            foreach ($permissions as $permission)
            {
                if(Permission::where('name',$permission)->count() === 0)
                {
                    \Spatie\Permission\Models\Permission::create(['name' => $permission])->assignRole(['owner','manager']);
                }
            }
    }
}
