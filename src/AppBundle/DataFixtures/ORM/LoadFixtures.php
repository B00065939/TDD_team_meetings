<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 23/02/2017
 * Time: 20:28
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\ActionStatus;
use AppBundle\Entity\AgendaStatus;
use AppBundle\Entity\MeetingStatus;
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

        $pr = new ProjectRole();
        $pr->setName('Project Supervisor');
        $manager->persist($pr);
        $manager->flush();
        $pr = new ProjectRole();
        $pr->setName('Project Member');
        $manager->persist($pr);
        $manager->flush();

        $ms = new MeetingStatus();
        $ms->setName("future");
        $manager->persist($ms);
        $manager->flush();
        $ms = new MeetingStatus();
        $ms->setName("past");
        $manager->persist($ms);
        $manager->flush();
        $ms = new MeetingStatus();
        $ms->setName("canceled");
        $manager->persist($ms);
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

        $as = new AgendaStatus();
        $as->setName("draft");
        $as->setDescription("agenda item before agenda deadline, or before leader will change it status to draft minute");
        $manager->persist($as);
        $manager->flush();

        $as = new AgendaStatus();
        $as->setName("draft minute");
        $as->setDescription("agenda item after agenda deadline, during meeting, before secretary will change it on next meeting to minute");
        $manager->persist($as);
        $manager->flush();

        $as = new AgendaStatus();
        $as->setName("minute");
        $as->setDescription("Minute item after 3rd accepting minutes from previous meeting no change possible to make");
        $manager->persist($as);
        $manager->flush();

        $as = new AgendaStatus();
        $as->setName("postponed");
        $as->setDescription("Agenda item postponed for future meeting without specifying it");
        $manager->persist($as);
        $manager->flush();

        $as = new AgendaStatus();
        $as->setName("postponed to");
        $as->setDescription("Agenda item postponed for future meeting");
        $manager->persist($as);
        $manager->flush();
        /************************** Action Status ********************************************/
        $actionStatus = new ActionStatus();
        $actionStatus->setName("in progress");
        $manager->persist($actionStatus);
        $manager->flush();

        $actionStatus = new ActionStatus();
        $actionStatus->setName("work under review");
        $manager->persist($actionStatus);
        $manager->flush();

        $actionStatus = new ActionStatus();
        $actionStatus->setName("late");
        $manager->persist($actionStatus);
        $manager->flush();

        $actionStatus = new ActionStatus();
        $actionStatus->setName("completed");
        $manager->persist($actionStatus);
        $manager->flush();

        $actionStatus = new ActionStatus();
        $actionStatus->setName("no longer required");
        $manager->persist($actionStatus);
        $manager->flush();

        $objects = Fixtures::load(
            __DIR__ . '/fixtures.yml',
            $manager,
            [
                'providers' => [$this]
            ]
        );
    }

    public function securityRole()
    {
        $values = [
            'ROLE_ADMIN',
            'ROLE_USER',
            'ROLE_SUPERVISOR'
        ];
        return $values[array_rand($values)];
    }

    public function projectRoleName()
    {
        $values = [
            'Project Treasurer',
            'Project Member'
        ];
        return $values[array_rand($values)];
    }
}