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
use AppBundle\Entity\Project;
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
        /********** Project Key Users Role **************************/
        $prLeaderRole = new ProjectRole();
        $prLeaderRole->setName('Project Leader');
        $manager->persist($prLeaderRole);
        $manager->flush();

        $prSecretaryRole = new ProjectRole();
        $prSecretaryRole->setName('Project Secretary');
        $manager->persist($prSecretaryRole);
        $manager->flush();


        $prSupRole = new ProjectRole();
        $prSupRole->setName('Project Supervisor');
        $manager->persist($prSupRole);
        $manager->flush();

        $prMemberRole = new ProjectRole();
        $prMemberRole->setName('Project Member');
        $manager->persist($prMemberRole);
        $manager->flush();

        /********** Meeting Status **************************/

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
        /**********  Key Users **************************/

        $user = new User();
        $user->setEmail('user@itb.ie');
        $user->setFullName("Michal Smigiel");
        $user->setPlainPassword('pass');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        $manager->flush();

        $sup = new User();
        $sup->setEmail('sup@itb.ie');
        $sup->setFullName("Super Visor");
        $sup->setPlainPassword('pass');
        $sup->setRoles(['ROLE_SUPERVISOR']);
        $manager->persist($sup);
        $manager->flush();

        $admin = new User();
        $admin->setEmail('admin@itb.ie');
        $admin->setFullName("Admin Istrator");
        $admin->setPlainPassword('pass');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();

        $leader = new User();
        $leader->setEmail('adam@itb.ie');
        $leader->setFullName("Adam Leader");
        $leader->setPlainPassword('pass');
        $leader->setRoles(['ROLE_USER']);
        $manager->persist($leader);
        $manager->flush();

        $secretary = new User();
        $secretary->setEmail('ewa@itb.ie');
        $secretary->setFullName("Ewa Secretary");
        $secretary->setPlainPassword('pass');
        $secretary->setRoles(['ROLE_USER']);
        $manager->persist($secretary);
        $manager->flush();

        /********** Agenda Status **************************/

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

        /*************************** Project Fest ***************************************/
        $project = new Project();
        $project->setTitle('First Project');
        $project->setLock(false);
        $manager->persist($project);
        $manager->flush();

        $phu = new ProjectHasUser();
        $phu->setProject($project);
        $phu->setUser($secretary);
        $phu->setProjectRole($prSecretaryRole);
        $manager->persist($phu);
        $manager->flush();

        $phu = new ProjectHasUser();
        $phu->setProject($project);
        $phu->setUser($leader);
        $phu->setProjectRole($prLeaderRole);
        $manager->persist($phu);
        $manager->flush();

        $phu = new ProjectHasUser();
        $phu->setProject($project);
        $phu->setUser($user);
        $phu->setProjectRole($prMemberRole);
        $manager->persist($phu);
        $manager->flush();

        $phu = new ProjectHasUser();
        $phu->setProject($project);
        $phu->setUser($sup);
        $phu->setProjectRole($prSupRole);
        $manager->persist($phu);
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