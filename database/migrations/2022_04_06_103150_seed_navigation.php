<?php

namespace Controlpanel\Vouchers;

use App\Enums\NavigationLocation;
use App\Helper\NavigationHelper;
use Illuminate\Database\Migrations\Migration;

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
        $helper = app(NavigationHelper::class);

        $helper->importNavigationOptionsFromFolder(NavigationLocation::sidebar, __DIR__. '/../../resources/sidebar');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $helper = app(NavigationHelper::class);

        $helper->removeNavigationOptionsFromFolder(__DIR__. '/../../resources/sidebar');
    }
};
