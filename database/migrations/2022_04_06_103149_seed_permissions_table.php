<?php

namespace Controlpanel\Vouchers;

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::findOrCreate('controlpanel.vouchers.read');
        Permission::findOrCreate('controlpanel.vouchers.write');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::findByName('controlpanel.vouchers.read')->delete();
        Permission::findByName('controlpanel.vouchers.write')->delete();
    }
};
