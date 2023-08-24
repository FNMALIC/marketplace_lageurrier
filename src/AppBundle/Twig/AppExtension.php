<?php

namespace AppBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('getRoles', array($this, 'getRolesFilter')),
        );
    }

    public function getRolesFilter($user)
    {
        return $user->getRoles();
    }
}
