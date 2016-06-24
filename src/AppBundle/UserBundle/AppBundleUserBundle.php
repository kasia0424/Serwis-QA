<?php

namespace AppBundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundleUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
