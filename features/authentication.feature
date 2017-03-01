Feature: Authentication
  In order to gain access to the user management area
  As an admin
  I need to be able to login and logout

  # Scenario cannot assume then is a data already in database so we need to put in there every time when we start test
  # and when we finishing it removing this data
#@javascript
#  Scenario: Login in to management page
#    Given There is an admin user "admin@wp.pl" with password "pass" with "ROLE_ADMIN"
#    And I am on "/"
#    When I fill in "Username" with "admin@wp.pl"
#    And I fill in "Password" with "pass"
#    And I press "Login"
#    Then I am on "/admin/adminpanel"
#    And I should see "Administrator Panel"
#    And I should see "Logout"

  Scenario Outline: Login in to management page
    Given There is an user "<email>" with password "<password>" with "<role>"
    And I am on "/"
    When I fill in "Username" with "<email>"
    And I fill in "Password" with "<password>"
    And I press "Login"
    Then I am on "<page_name>"
    And I should see "<result>"
    And I should see "Logout"
    Examples:
      | email       | password | role       | page_name         | result              |
      | admin@wp.pl | pass     | ROLE_ADMIN | /admin/adminpanel | Administrator Panel |
      | user@wp.pl  | pass     | ROLE_USER  | /user/userpanel   | User Panel          |
      | sup@wp.pl   | pass     | ROLE_SUP   | /sup/suppanel     | Supervisor Panel   |


#@javascript
  Scenario: Logout from administrator panel
    Given I am logged in as an admin
    When I follow "logout"
    Then I should see "Login!"

  Scenario: Logout from user panel
    Given I am logged in as an user
    When I follow "logout"
    Then I should see "Login!"
#@javascript
  Scenario: Logout from supervisor panel
    Given I am logged in as an supervisor
    When I follow "logout"
    Then I should see "Login!"