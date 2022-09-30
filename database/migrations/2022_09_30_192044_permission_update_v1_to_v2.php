<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermissionUpdateV1ToV2 extends Migration
{
    
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');
        $userModelNamespace = 'App\User'; // Change this value if you didn't use default User namespace in V1
        
        // Rename V1 tables
        Schema::rename('permissions', 'permissions_v1');
        Schema::rename('roles', 'roles_v1');
        Schema::rename('role_has_permissions', 'role_has_permissions_v1');
        
        // Drop V1 foreign key constraints
        Schema::table('role_has_permissions_v1', function ($table) {
            $table->dropForeign('role_has_permissions_permission_id_foreign');
            $table->dropForeign('role_has_permissions_role_id_foreign');
        });
        
        // Create V2.28 tables
        Schema::create($tableNames['permissions'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });
        
        Schema::create($tableNames['roles'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('guard_name');
            $table->timestamps();
        });
        
        Schema::create($tableNames['model_has_permissions'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('permission_id');
            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type', ], 'model_has_permissions_model_id_model_type_index');
            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
            $table->primary(['permission_id', $columnNames['model_morph_key'], 'model_type'],
                'model_has_permissions_permission_model_type_primary');
        });
        
        Schema::create($tableNames['model_has_roles'], function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('role_id');
            $table->string('model_type');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'model_type', ], 'model_has_roles_model_id_model_type_index');
            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
            $table->primary(['role_id', $columnNames['model_morph_key'], 'model_type'],
                'model_has_roles_role_model_type_primary');
        });
        
        Schema::create($tableNames['role_has_permissions'], function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('permission_id');
            $table->unsignedInteger('role_id');
            $table->foreign('permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');
            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');
            $table->primary(['permission_id', 'role_id'], 'role_has_permissions_permission_id_role_id_primary');
            
			app('cache')
                ->store(config('permission.cache.store') != 'default' ? config('permission.cache.store') : null)
                ->forget(config('permission.cache.key'));
        });
        
        // Migrate V1 tables to V2 tables
        $roles = collect(DB::table('roles_v1')->select()->get())->map(function($x) { return (array) $x + ['guard_name' => config('auth.defaults.guard')]; })->toArray();
        DB::table($tableNames['roles'])->insert($roles);
        
        $permissions = collect(DB::table('permissions_v1')->select()->get())->map(function($x) { return (array) $x + ['guard_name' => config('auth.defaults.guard')]; })->toArray();
        DB::table($tableNames['permissions'])->insert($permissions);
        
        $model_has_permissions = collect(DB::table('user_has_permissions')->select(['user_id AS model_id', 'permission_id'])->get())->map(function($x) use ($userModelNamespace) { return (array) $x + ['model_type' => $userModelNamespace]; })->toArray();
        DB::table($tableNames['model_has_permissions'])->insert($model_has_permissions);
        
        $model_has_roles = collect(DB::table('user_has_roles')->select(['user_id AS model_id', 'role_id'])->get())->map(function($x) use ($userModelNamespace) { return (array) $x + ['model_type' => $userModelNamespace]; })->toArray();
        DB::table($tableNames['model_has_roles'])->insert($model_has_roles);
        
        $role_has_permissions = collect(DB::table('role_has_permissions_v1')->select()->get())->map(function($x) { return (array) $x; })->toArray();
        DB::table($tableNames['role_has_permissions'])->insert($role_has_permissions);
        
        // Drop V1 tables
        // Remove this lines if you want to keep the renamed V1 tables
        Schema::drop('role_has_permissions_v1');
        Schema::drop('user_has_permissions');
        Schema::drop('user_has_roles');
        Schema::drop('roles_v1');
        Schema::drop('permissions_v1');
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // WARNING: You can't rollback to V1 tables with this script!
        
        $tableNames = config('permission.table_names');
        Schema::drop($tableNames['role_has_permissions']);
        Schema::drop($tableNames['model_has_roles']);
        Schema::drop($tableNames['model_has_permissions']);
        Schema::drop($tableNames['roles']);
        Schema::drop($tableNames['permissions']);
    }
}
