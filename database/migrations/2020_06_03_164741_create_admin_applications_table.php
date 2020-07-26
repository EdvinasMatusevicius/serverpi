<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->string('applicationName')->unique();
            $table->string('slug')->unique();
            $table->integer('language')->require();
            $table->string('database')->nullable();
            $table->string('giturl')->require();
            $table->boolean('deployed')->default(0);
            $table->timestamps();
        });

        Schema::table('admin_applications', function (Blueprint $table) {
            $table->foreign('admin_id')
            ->references('id')
            ->on('admins')
            ->onUpdate('CASCADE')
            ->onDelete('CASCADE');
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_applications');
    }
}
