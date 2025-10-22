<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Group;
use App\Models\Project;
use App\Models\Grade;
use App\Models\GradeType;
use App\Models\Week;
use App\Models\WeekType;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === ROLES (Spatie) ===
        $roles = ['dosen', 'asisten', 'mahasiswa'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // === USERS ===
        $users = [
            [
                'id' => 1,
                'name' => 'dosen1',
                'email' => 'dosen1@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'id' => 2,
                'name' => 'asisten1',
                'email' => 'asisten1@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'id' => 3,
                'name' => 'mahasiswa1',
                'email' => 'mhs1@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'id' => 4,
                'name' => 'mahasiswa2',
                'email' => 'mhs2@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'id' => 5,
                'name' => 'mahasiswa3',
                'email' => 'mhs3@example.com',
                'password' => Hash::make('password'),
            ],
        ];

        foreach ($users as $data) {
            $user = User::updateOrCreate(['id' => $data['id']], $data);

            // Assign role based on username
            if (str_contains($user->name, 'dosen')) {
                $user->assignRole('dosen');

                // Use Eloquent relationship
                $user->dosen_data()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nidn' => '123456789',
                        'fakultas' => 'Basis Data',
                    ]
                );

            } elseif (str_contains($user->name, 'asisten')) {
                $user->assignRole('asisten');

                $user->asisten_data()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'tahun_ajaran' => 2024,
                        'nim' => '12/1238/12312311'
                    ]
                );

            } else {
                $user->assignRole('mahasiswa');

                $user->mahasiswa_data()->updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'nim' => '21000123',
                        'angkatan' => 2021,
                        'prodi' => 'Informatika',
                        'jabatan' => 'BE',
                    ]
                );
            }
        }


        // === PROJECTS ===
        $project = Project::create([
            'nama_projek' => 'Sistem Informasi Akademik',
            'deskripsi' => 'Aplikasi untuk mengelola data kampus',
            'semester' => 5,
            'tahun_ajaran' => '2024',
            'user_id' => 1, // dosen PO
            'asisten_id' => 2
        ]);

        // === GROUPS ===
        $group = Group::create([
            'name' => 'Kelompok 1',
            'project_id' => $project->id,
        ]);

        DB::table('group_members')->insert([
            'user_id' => 3, // mahasiswa
            'group_id' => $group->id,
        ]);

        // === Week Types ===
        $gradeTypes = [
            ['name' => 'kerapihan','percentage' => 10],
            ['name' => 'estetika','percentage' => 30],
            ['name' => 'kecepatan','percentage' => 50],
        ];

        foreach ($gradeTypes as $type) {
            GradeType::firstOrCreate($type);
        }

        // === Week Types ===
        $weekTypes = [
            ['name' => 'week 1','percentage' => 10],
            ['name' => 'week 2','percentage' => 30],
            ['name' => 'UTS','percentage' => 50],
            ['name' => 'UAS','percentage' => 90],
        ];

        foreach ($weekTypes as $type) {
            $weekType = WeekType::firstOrCreate($type);
            $weekType->gradeType()->sync([2, 1]);
        }

        $weekType = WeekType::first();
        $asisten = User::find(2);
        $gradeTypes = $weekType->gradeType;

        $week = Week::create([
            'grader_id' => $asisten->id,
            'date' => now(),
            'project_id' => $project->id,
            'week_type_id' => $weekType->id,
            'notes' => "kurang rapih"
        ]);

        foreach ($gradeTypes as $gradeType) {
            $week->grades()->create([
                'grade_type_id' => $gradeType->id,
                'grade' => 100
            ]);
        }

    }
}
