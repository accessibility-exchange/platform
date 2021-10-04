<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->json('goals')->nullable();
            $table->json('impact')->nullable();
            $table->json('out_of_scope')->nullable();
            $table->boolean('virtual_consultation')->nullable();
            $table->json('timeline')->nullable();
            $table->json('payment_terms')->nullable();
            $table->boolean('existing_clients')->nullable();
            $table->boolean('prospective_clients')->nullable();
            $table->boolean('employees')->nullable();
            $table->json('priority_outreach')->nullable();
            $table->json('locality')->nullable();
            $table->json('location_description')->nullable();
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->json('anything_else')->nullable();
            $table->boolean('flexible_deadlines')->nullable();
            $table->boolean('flexible_breaks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn([
                'goals',
                'impact',
                'out_of_scope',
                'virtual_consultation',
                'timeline',
                'payment_terms',
                'existing_clients',
                'prospective_clients',
                'employees',
                'priority_outreach',
                'locality',
                'location_description',
                'min',
                'max',
                'anything_else',
                'flexible_deadlines',
                'flexible_breaks',
            ]);
        });
    }
}
