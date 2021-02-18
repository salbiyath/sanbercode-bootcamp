<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Role;

class AddRoleIdToUsersTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $defaultRoleId = DB::table('roles')
            ->where('name', '=', 'user')
            ->value('id');

        Schema::table('users', function (Blueprint $table) {
            $table->uuid('role_id')->default($defaultRoleId);
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('role_id');
            $table->dropColumn('role_id');
        });
    }
}
