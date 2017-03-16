<?php
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 23/02/2017
 * Time: 20:28
 */

namespace AppBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {

        $objects = Fixtures::load(
            __DIR__.'/fixtures.yml',
            $manager,
            [
                'providers'=>[$this]
            ]
        );
    }

    public function projectRoleName()
    {
        $values =[
            'project leader',
            'project secretary',
            'project treasurer',
            'project member'
        ];
        return $values[array_rand($values)];
    }
}