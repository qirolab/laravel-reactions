<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Qirolab\Laravel\Reactions\Helper;

/**
 * Class CreateLoveLikesTable.
 */
class CreateReactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('reactions.table_name', 'reactions'), function (Blueprint $table) {
            $userIdColumn = Helper::resolveReactsIdColumn();

            $table->increments('id');
            $table->integer($userIdColumn)->unsigned()->index();
            $table->morphs('reactable');
            $table->string('type')->nullable();
            $table->timestamps();
            $table->unique([
                'reactable_type',
                'reactable_id',
                $userIdColumn,
            ], 'react_user_unique');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reactions');
    }
}
