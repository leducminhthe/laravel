<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\User;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['Admin','Admin','Vai trò có quyền cao nhất', 1],
            ['Manager','Quản lý','Vai trò có tất cả quyền trừ quyền quản lý phần quyền', 1],
            ['Teacher','Giảng viên','Vai trò giảng viên', 1],
            ['Editor','Biên soạn tài liệu', 'Vai trờ người biên tập', 1],
            ['User','Người dùng', 'Vai trờ người dùng', 1],
            ['QLHT','Quản lý hệ thống E-learning', 'Quản lý hệ thống E-learning', 2],
        ];
        foreach ($roles as $key => $value) {
            Role::updateOrCreate(
                [
                    'name' => $value[1]
                ],
                [
                    'code' => $value[0],
                    'name' => $value[1],
                    'description'=>$value[2],
                    'type' => $value[3],
                    'guard_name' => 'web',
                    'created_by'=>2,
                    'updated_by'=>2,
                    'unit_by' => 1,
                ]
            );
        }
        User::find(1)->assignRole('Admin');
        User::find(2)->assignRole('Admin');
    }
}
