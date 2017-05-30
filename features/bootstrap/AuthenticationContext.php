<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

/**
 * Defines application features from the specific context.
 */
class AuthenticationContext extends RawMinkContext implements Context
{
    use \Behat\Symfony2Extension\Context\KernelDictionary;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $purger = new ORMPurger($this->getContainer()->get('doctrine')->getManager());
        $purger->purge();
    }

    /**
     * @Given There is an user :fullName :email with password :password with :role
     * @param $email
     * @param $password
     * @param $role
     * @param $fullName
     */
    public function thereIsAnUserWithPassword($email, $password, $role , $fullName)
    {
        $user = new \AppBundle\Entity\User();
        $user->setEmail($email);
        $user->setFullName($fullName);
        $user->setPlainPassword($password);
        $user->setRoles(array($role));
        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();
    }

    /**
     * @Given I am logged in as an admin
     */
    public function iAmLoggedInAsAnAdmin()
    {
        $email = 'admin@wp.pl';
        $password = 'pass';
        $roles = 'ROLE_ADMIN';
        $fullName = 'Test Admin';

        $this->thereIsAnUserWithPassword($email,$password,$roles, $fullName);
        $this->visitPath('/');
        $this->getPage()->fillField('Username', $email);
        $this->getPage()->fillField('Password', $password);
        $this->getPage()->pressButton('Login');
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    /**
     * @return \Behat\Mink\Element\DocumentElement
     */
    private function getPage()
    {
        return $this->getSession()->getPage();
    }

    /**
     * @Given I am logged in as an user
     */
    public function iAmLoggedInAsAnUser()
    {
        $email = 'user@wp.pl';
        $password = 'pass';
        $roles = 'ROLE_USER';
        $fullName = 'Test User';

        $this->thereIsAnUserWithPassword($email, $password, $roles, $fullName);
        $this->visitPath('/');
        $this->getPage()->fillField('Username', $email);
        $this->getPage()->fillField('Password', $password);
        $this->getPage()->pressButton('Login');
    }

    /**
     * @Given I am logged in as an supervisor
     */
    public function iAmLoggedInAsAnSupervisor()
    {
        $email = 'sup@wp.pl';
        $password = 'pass';
        $roles = 'ROLE_SUPERVISOR';
        $fullName = 'Test Sup';

        $this->thereIsAnUserWithPassword($email, $password, $roles, $fullName);
        $this->visitPath('/');
        $this->getPage()->fillField('Username', $email);
        $this->getPage()->fillField('Password', $password);
        $this->getPage()->pressButton('Login');
    }
}
