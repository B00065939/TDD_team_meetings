<?php
/**
 * Created by PhpStorm.
 * User: bemben
 * Date: 20/03/2017
 * Time: 21:00
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ProjectRepository extends EntityRepository
{
    public function createUnlockedProjectsQueryBuilder()
    {
        return $this->createQueryBuilder('project')
            ->where('project.locked = false');
    }
}