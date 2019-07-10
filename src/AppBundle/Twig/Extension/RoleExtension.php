<?php

namespace AppBundle\Twig\Extension;

use Twig_Filter_Method;
use FOS\UserBundle\Model\User;
use Symfony\Component\HttpFoundation\Session\Session;

class RoleExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('display_role', [$this, 'displayRole']),
        ];
    }

    /**
     * @param User $user
     * @return string
     */
    public function displayRole(User $user = null)
    {
        if (!$user) {
            return '';
        }

        if ($user->hasRole('ROLE_SUPER_ADMIN')) {
            return 'Super Admin';
        } elseif ($user->hasRole('ROLE_FOCAL')) {
            return 'Point focal';
        } elseif ($user->hasRole('ROLE_OFFICIER')) {
            return 'Officier de liaison';
        }
        /* elseif ($user->hasRole('ROLE_ADMIN')) {
            return 'Admin';
        } */

        return '';
    }

    public function getName()
    {
        return 'role_extension';
    }
}