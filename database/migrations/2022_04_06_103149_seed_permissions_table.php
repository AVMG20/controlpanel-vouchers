<?php

namespace Controlpanel\Vouchers;

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

//TODO use seeders instead
//REASON i haven't. seeders can't be automatically published, but still can be ran by the user
//since this is a core package, i wanted it to be zero configuration during installation
//this simple package is also not really gone change permissions and navigation options anyways.
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
