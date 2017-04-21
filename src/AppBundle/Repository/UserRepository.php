<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    public function createAlphabeticalUSERQueryBuilder()
    {
        $em = $this>$this->getEntityManager();

        return $this->createQueryBuilder('user')
            ->orderBy('user.fullName','ASC');
    }
}