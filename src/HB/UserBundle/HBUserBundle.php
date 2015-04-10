<?php

namespace HB\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class HBUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
