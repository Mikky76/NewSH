<?php

namespace SH\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SHUserBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
