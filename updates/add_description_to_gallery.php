<?php

namespace Yamobile\Gallery\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddFormTextToGallery extends Migration
{
    public function up()
    {
        Schema::table('yamobile_gallery_list', function ($table) {
            $table->text('description')->nullable();
        });
    }

    public function down()
    {
        Schema::table('yamobile_gallery_list', function ($table) {
            $table->dropColumn('description');
        });
    }
}
