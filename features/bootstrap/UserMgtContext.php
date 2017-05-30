<?php
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

require_once __DIR__.'/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';
/**
 * Created by PhpStorm.
 * User: Bemben
 * Date: 02/03/2017
 * Time: 13:14
 */
class UserMgtContext extends RawMinkContext implements Context
{

    /**
     * @Then I should see table of users with at least :numberOfUsers rows
     */
    public function iShouldSeeTableOfUsersWithAtLeastRows($numberOfUsers)
    {
        $rows = $this->getPage()->findAll('css','tr');
        assertLessThanOrEqual(count($rows),$numberOfUsers,"");
        //throw new \Behat\Behat\Tester\Exception\PendingException();
    }

    /**
     * @Then I should see list of :numberOfUsers user(s)
     * @param $numberOfUsers
     */
    public function iShouldSeeListOfUsers($numberOfUsers)
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }

    /**
     * @Given There is/are :numberOfUsers user(s) in database
     * @param $numberOfUsers
     */
    public function thereIsUsersInDatabase($numberOfUsers)
    {
        for ($i = 0; $i < $numberOfUsers; $i++) {
            $user = new \AppBundle\Entity\User();
            $user->setEmail("user".$i."@wp.pl");
            $user->setPlainPassword("pass".$i);
            $user->setRoles(['ROLE_USER']);
            $user->setFullName('Test'.$i.' User');
            $em = $this->getEntityManager();
            $em->persist($user);
            $em->flush();
        }
    }

    use \Behat\Symfony2Extension\Context\KernelDictionary;
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
     *
     * @BeforeScenario
     */
    public function clearData()
    {
        $purger = new ORMPurger($this->getContainer()->get('doctrine')->getManager());
        $purger->purge();
    }
}