<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('email')->unique()->index();
            $table->string('password');
            $table->string('userable_type');
            $table->integer('userable_id')->unsigned();
            $table->boolean('active')->default(true); // Been payed for
            $table->boolean('activated')->default(false); // Activated the account via email
            // Email Preferences  Order: database, mail. 
            // IMPORTANT: should be synced with the constants
            $table->integer('notify_report_created')->default(2); // default only mail

            $table->softDeletes();
            $table->rememberToken();
            $table->string('api_token', 60)->unique();
            $table->timestamps();
        });

        DB::unprepared("
            ALTER TABLE `users`
                ADD CHECK (userable_type IN (
                    'App\Administrator',
                    'App\Client',
                    'App\Technician',
                    'App\Supervisor'
                ));
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
