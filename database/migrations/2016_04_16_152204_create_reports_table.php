<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->dateTime('completed');
            $table->enum('on_time', ['early', 'onTime', 'late']);
            $table->unsignedTinyInteger('ph');
            $table->unsignedTinyInteger('chlorine');
            $table->unsignedTinyInteger('temperature');
            $table->unsignedTinyInteger('turbidity');
            $table->unsignedTinyInteger('salt')->nullable();
            $table->decimal('latitude', 9, 6)->nullable();
            $table->decimal('longitude', 9, 6)->nullable();
            $table->decimal('altitude', 8, 2)->nullable();
            $table->decimal('accuracy', 8, 2)->nullable();
            $table->integer('service_id')->unsigned();
            $table->integer('technician_id')->unsigned();
            $table->integer('seq_id')->unsigned()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('reports', function(Blueprint $table){
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->onDelete('cascade');
            $table->foreign('technician_id')
                ->references('id')
                ->on('technicians')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('reports');
    }
}
