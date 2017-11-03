<?php
/**
 * Created by PhpStorm.
 * User: Danny
 * Date: 03/11/2017
 * Time: 09:38
 */

namespace CBS;


class app
{
    public function init() {

        $menu = new menu();
        $menu->printWelcomeScreen();
        $menu->printMainMenu();

    }
}