<?php

namespace Yamobile\Gallery\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;

class AddHeadingToGallery extends Migration
{
    public function up()
    {
        Schema::table('yamobile_gallery_list', function ($table) {
            $table->text('heading')->nullable();
        });
    }

    public function down()
    {
        Schema::table('yamobile_gallery_list', function ($table) {
            $table->dropColumn('heading');
        });
    }
}
