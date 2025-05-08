<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"Content Management","guard_name":"web","permissions":["view_artikel","view_any_artikel","create_artikel","update_artikel","restore_artikel","restore_any_artikel","replicate_artikel","reorder_artikel","delete_artikel","delete_any_artikel","view_event","view_any_event","create_event","update_event","restore_event","restore_any_event","replicate_event","reorder_event","delete_event","delete_any_event","view_galeri","view_any_galeri","create_galeri","update_galeri","restore_galeri","restore_any_galeri","replicate_galeri","reorder_galeri","delete_galeri","delete_any_galeri","view_konten::slider","view_any_konten::slider","update_konten::slider","restore_konten::slider","restore_any_konten::slider","replicate_konten::slider","reorder_konten::slider","view_media::sosial","view_any_media::sosial","create_media::sosial","update_media::sosial","restore_media::sosial","restore_any_media::sosial","replicate_media::sosial","reorder_media::sosial","delete_media::sosial","delete_any_media::sosial","view_produk","view_any_produk","create_produk","update_produk","restore_produk","restore_any_produk","replicate_produk","reorder_produk","delete_produk","delete_any_produk","view_unduhan","view_any_unduhan","create_unduhan","update_unduhan","restore_unduhan","restore_any_unduhan","replicate_unduhan","reorder_unduhan","delete_unduhan","delete_any_unduhan"]},{"name":"Customer Service","guard_name":"web","permissions":["view_feedback","view_any_feedback","update_feedback","restore_feedback","restore_any_feedback","replicate_feedback","reorder_feedback","view_lamaran","view_any_lamaran","update_lamaran","restore_lamaran","restore_any_lamaran","replicate_lamaran","reorder_lamaran","view_lowongan","view_any_lowongan","update_lowongan","restore_lowongan","restore_any_lowongan","replicate_lowongan","reorder_lowongan","view_testimoni","view_any_testimoni","update_testimoni","restore_testimoni","restore_any_testimoni","replicate_testimoni","reorder_testimoni"]},{"name":"super_admin","guard_name":"web","permissions":["view_artikel","view_any_artikel","create_artikel","update_artikel","restore_artikel","restore_any_artikel","replicate_artikel","reorder_artikel","delete_artikel","delete_any_artikel","view_event","view_any_event","create_event","update_event","restore_event","restore_any_event","replicate_event","reorder_event","delete_event","delete_any_event","view_galeri","view_any_galeri","create_galeri","update_galeri","restore_galeri","restore_any_galeri","replicate_galeri","reorder_galeri","delete_galeri","delete_any_galeri","view_konten::slider","view_any_konten::slider","update_konten::slider","restore_konten::slider","restore_any_konten::slider","replicate_konten::slider","reorder_konten::slider","view_media::sosial","view_any_media::sosial","create_media::sosial","update_media::sosial","restore_media::sosial","restore_any_media::sosial","replicate_media::sosial","reorder_media::sosial","delete_media::sosial","delete_any_media::sosial","view_produk","view_any_produk","create_produk","update_produk","restore_produk","restore_any_produk","replicate_produk","reorder_produk","delete_produk","delete_any_produk","view_unduhan","view_any_unduhan","create_unduhan","update_unduhan","restore_unduhan","restore_any_unduhan","replicate_unduhan","reorder_unduhan","delete_unduhan","delete_any_unduhan","view_feedback","view_any_feedback","update_feedback","restore_feedback","restore_any_feedback","replicate_feedback","reorder_feedback","view_lamaran","view_any_lamaran","update_lamaran","restore_lamaran","restore_any_lamaran","replicate_lamaran","reorder_lamaran","view_lowongan","view_any_lowongan","update_lowongan","restore_lowongan","restore_any_lowongan","replicate_lowongan","reorder_lowongan","view_testimoni","view_any_testimoni","update_testimoni","restore_testimoni","restore_any_testimoni","replicate_testimoni","reorder_testimoni","create_lowongan","delete_lowongan","delete_any_lowongan","force_delete_artikel","force_delete_any_artikel","force_delete_event","force_delete_any_event","force_delete_galeri","force_delete_any_galeri","force_delete_lowongan","force_delete_any_lowongan","force_delete_media::sosial","force_delete_any_media::sosial","view_mitra","view_any_mitra","create_mitra","update_mitra","restore_mitra","restore_any_mitra","replicate_mitra","reorder_mitra","delete_mitra","delete_any_mitra","force_delete_mitra","force_delete_any_mitra","force_delete_produk","force_delete_any_produk","view_profil::perusahaan","view_any_profil::perusahaan","update_profil::perusahaan","restore_profil::perusahaan","restore_any_profil::perusahaan","replicate_profil::perusahaan","reorder_profil::perusahaan","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_struktur::organisasi","view_any_struktur::organisasi","create_struktur::organisasi","update_struktur::organisasi","restore_struktur::organisasi","restore_any_struktur::organisasi","replicate_struktur::organisasi","reorder_struktur::organisasi","delete_struktur::organisasi","delete_any_struktur::organisasi","force_delete_struktur::organisasi","force_delete_any_struktur::organisasi","force_delete_unduhan","force_delete_any_unduhan","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","view_feature::toggle","view_any_feature::toggle","update_feature::toggle"]},{"name":"Director","guard_name":"web","permissions":["view_artikel","view_any_artikel","view_event","view_any_event","view_galeri","view_any_galeri","view_konten::slider","view_any_konten::slider","view_media::sosial","view_any_media::sosial","view_produk","view_any_produk","view_unduhan","view_any_unduhan","view_feedback","view_any_feedback","view_lamaran","view_any_lamaran","view_lowongan","view_any_lowongan","view_testimoni","view_any_testimoni","view_mitra","view_any_mitra","create_mitra","update_mitra","restore_mitra","restore_any_mitra","replicate_mitra","reorder_mitra","delete_mitra","delete_any_mitra","view_profil::perusahaan","view_any_profil::perusahaan","update_profil::perusahaan","restore_profil::perusahaan","restore_any_profil::perusahaan","replicate_profil::perusahaan","reorder_profil::perusahaan","view_role","view_any_role","view_struktur::organisasi","view_any_struktur::organisasi","create_struktur::organisasi","update_struktur::organisasi","restore_struktur::organisasi","restore_any_struktur::organisasi","replicate_struktur::organisasi","reorder_struktur::organisasi","delete_struktur::organisasi","delete_any_struktur::organisasi","view_user","view_any_user"]}]';
        $directPermissions = '{"161":{"name":"delete_konten::slider","guard_name":"web"},"162":{"name":"delete_any_konten::slider","guard_name":"web"},"163":{"name":"delete_feedback","guard_name":"web"},"164":{"name":"delete_any_feedback","guard_name":"web"},"165":{"name":"delete_lamaran","guard_name":"web"},"166":{"name":"delete_any_lamaran","guard_name":"web"},"167":{"name":"force_delete_feedback","guard_name":"web"},"168":{"name":"force_delete_any_feedback","guard_name":"web"},"169":{"name":"force_delete_konten::slider","guard_name":"web"},"170":{"name":"force_delete_any_konten::slider","guard_name":"web"},"171":{"name":"force_delete_lamaran","guard_name":"web"},"172":{"name":"force_delete_any_lamaran","guard_name":"web"},"173":{"name":"delete_profil::perusahaan","guard_name":"web"},"174":{"name":"delete_any_profil::perusahaan","guard_name":"web"},"175":{"name":"force_delete_profil::perusahaan","guard_name":"web"},"176":{"name":"force_delete_any_profil::perusahaan","guard_name":"web"},"177":{"name":"delete_testimoni","guard_name":"web"},"178":{"name":"delete_any_testimoni","guard_name":"web"},"179":{"name":"force_delete_testimoni","guard_name":"web"},"180":{"name":"force_delete_any_testimoni","guard_name":"web"},"181":{"name":"create_profil::perusahaan","guard_name":"web"},"182":{"name":"create_konten::slider","guard_name":"web"},"183":{"name":"create_feedback","guard_name":"web"},"184":{"name":"create_lamaran","guard_name":"web"},"185":{"name":"create_testimoni","guard_name":"web"}}';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
