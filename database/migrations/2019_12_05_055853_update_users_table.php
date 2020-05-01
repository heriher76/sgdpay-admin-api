<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users', function (Blueprint $table) {
          $table->string('hp')->nullable();
          $table->string('address')->nullable();
          $table->string('status')->nullable();
          $table->string('photo')->nullable();
          $table->string('username')->unique()->nullable();
          $table->integer('type')->nullable();
          $table->integer('majors')->nullable();
          $table->integer('faculty')->nullable();
          $table->timestamp('verified')->nullable();
          $table->timestamp('pin')->nullable();
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
        $table->dropColumn('hp');
        $table->dropColumn('address');
        $table->dropColumn('status');
        $table->dropColumn('photo');
        $table->dropColumn('username');
        $table->dropColumn('type');
        $table->dropColumn('majors');
        $table->dropColumn('faculty');
        $table->dropColumn('password');
        $table->dropColumn('verified');
        $table->dropColumn('pin');
      });
    }
}
