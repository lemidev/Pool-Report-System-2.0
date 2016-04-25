<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupervisorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supervisors', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('last_name');
            $table->string('cellphone');
            $table->string('address');
            $table->string('email')->index();
            $table->string('password');
            $table->char('language', 2);
            $table->text('comments');
            $table->integer('user_id')->unsigned();
            $table->integer('seq_id')->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('supervisors', function(Blueprint $table){
            $table->unique(array('user_id', 'email'), 'supervisors_email_unique');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('supervisors');
    }
}
