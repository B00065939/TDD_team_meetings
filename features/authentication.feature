Feature: Authentication
  In order to gain access to the user or admin or supervisor panel
  As an user or admin or supervisor
  I need to be able to login and logout

  # Scenario cannot assume then is a data already in database so we need to put in there every time when we start test
  # and when we finishing it removing this data

#  Scenario: Login in to  dashboard page
#    Given There is an admin user "admin@wp.pl" with password "pass" with "ROLE_ADMIN"
#    And I am on "/"
#    When I fill in "Username" with "admin@wp.pl"
#    And I fill in "Password" with "pass"
#    And I press "Login"
#    Then I am on "/admin/adminpanel"
#    And I should see "Administrator Panel"
#    And I should see "Logout

#@javascript
  Scenario Outline: Login in to dashboard page
    Given There is an user "<full_name>" "<email>" with password "<password>" with "<role>"
    And I am on "/"
    When I fill in "Username" with "<email>"
    And I fill in "Password" with "<password>"
    And I press "Login"
    Then I am on "<page_name>"
    And I should see "<result>"
    And I should see "Logout"

    Examples:
      | email        | full_name  | password | role            | page_name         | result           |
      | admin@itb.pl | Test Admin | pass     | ROLE_ADMIN      | /admin/adminpanel | Admin Panel      |
      | user@itb.pl  | Test User  | pass     | ROLE_USER       | /user/userpanel   | User Panel       |
      | sup@itb.pl   | Test Sup   | pass     | ROLE_SUPERVISOR | /sup/suppanel     | Supervisor Panel |


#@javascript
  Scenario: Logout from admin panel
    Given I am logged in as an admin
    When I follow "Logout"
    Then I should see "Login please!"
#@javascript
  Scenario: Logout from user panel
    Given I am logged in as an user
    When I follow "Logout"
    Then I should see "Login please!"
#@javascript
  Scenario: Logout from supervisor panel
    Given I am logged in as an supervisor
    When I follow "Logout"
    Then I should see "Login please!"