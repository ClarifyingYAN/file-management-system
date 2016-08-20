<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Role;
use App\Permission;

class RbacInitSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // role -- admin.
        $admin = new Role;
        $admin->name = 'admin';
        $admin->display_name = 'Admin';
        $admin->save();

        // role -- user.
        $user = new Role;
        $user->name = 'user';
        $user->display_name = 'User';
        $user->save();

        // add admin user.
        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
        ]);

        // assign role to users.
        $userAdmin = User::where('email', '=', 'admin@admin.com')->first();
        $userAdmin->attachRole($userAdmin);

        // permission -- manage user.
        $manageUser = new Permission;
        $manageUser->name = 'manage-user';
        $manageUser->save();

        // permission -- manage file.
        $manageFile = new Permission();
        $manageFile->name = 'manage-file';
        $manageFile->save();

        // add admin permission.
        $admin->attachPermissions([$manageUser, $manageFile]);

        // add user permission.
        $user->attachPermission($manageFile);
    }

}