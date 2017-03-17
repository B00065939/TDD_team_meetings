<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    public function createAlphabeticalUSERQueryBuilder()
    {
        return $this->createQueryBuilder('user')
            ->orderBy('user.fullName','ASC');
    }
}