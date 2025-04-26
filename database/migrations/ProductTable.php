<?php

namespace Database\Migrations;

use {{NAMESPACE}}\App\Schema;

class ProductTable
{
    public function up()
    {
        Schema::create('product', function ($table) {
            // Define columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('product');
    }
}
