<?php
/**
 * Created by PhpStorm.
 * User: bemben
 * Date: 20/03/2017
 * Time: 21:24
 */

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class ProjectRoleRepository extends EntityRepository
{
    public function createNotKeyRoleQueryBuilder()
    {
        $prjLeader = 'Project Leader';
        $prjSecretary = 'Project Secretary';
        return $this->createQueryBuilder('projectRole')
            ->where('projectRole.name != :project_leader and projectRole.name != :project_secretary')
            ->setParameters([
                'project_leader' => $prjLeader,
                'project_secretary' => $prjSecretary
            ]);

    }

}