<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 23/02/2017
 * Time: 20:28
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\ProjectHasUser;
use AppBundle\Entity\ProjectRole;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $pr = new ProjectRole();
        $pr->setName('Project Leader');
        $manager->persist($pr);
        $manager->flush();

        $pr = new ProjectRole();
        $pr->setName('Project Secretary');
        $manager->persist($pr);
        $manager->flush();

        $u = new User();
        $u->setEmail('user@itb.ie');
        $u->setFullName("Michal Smigiel");
        $u->setPlainPassword('pass');
        $u->setRoles(['ROLE_USER']);
        $manager->persist($u);
        $manager->flush();

        $u = new User();
        $u->setEmail('sup@itb.ie');
        $u->setFullName("Super Visor");
        $u->setPlainPassword('pass');
        $u->setRoles(['ROLE_SUPERVISOR']);
        $manager->persist($u);
        $manager->flush();

        $u = new User();
        $u->setEmail('admin@itb.ie');
        $u->setFullName("Admin Istrator");
        $u->setPlainPassword('pass');
        $u->setRoles(['ROLE_ADMIN']);
        $manager->persist($u);
        $manager->flush();

        $objects = Fixtures::load(
            __DIR__.'/fixtures.yml',
            $manager,
            [
                'providers'=>[$this]
            ]
        );
    }
    public function securityRole() {
        $values =[
            'ROLE_ADMIN',
            'ROLE_USER',
            'ROLE_SUPERVISOR'
        ];
        return $values[array_rand($values)];
    }
    public function projectRoleName()
    {
        $values =[
           'Project Treasurer',
            'Project Member'
        ];
        return $values[array_rand($values)];
    }
}