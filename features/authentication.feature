Feature: Authentication
  In order to gain access to the user management area
  As an admin
  I need to be able to login and logout

  # Scenario cannot assume then is a data already in database so we need to put in there every time when we start test
  # and when we finishing it removing this data
#@javascript
  Scenario: Login in to management page
    Given There is an admin user "admin@wp.pl" with password "pass" with "ROLE_ADMIN"
    And I am on "/"
    When I fill in "Username" with "admin@wp.pl"
    And I fill in "Password" with "pass"
    And I press "Login"
    Then I am on "/admin/adminpanel"
    And I should see "Administrator Panel"
    And I should see "Logout"
#@javascript
  Scenario: Logout from administrator panel
    Given I am logged in as an  admin
    And I am on "/admin/adminpanel"
   And I should see "Administrator Panel"

   And I should see "logout"
#  Then I should be on "/admin/adminpanel"
#    Then print last response
    When I follow "logout"
    Then I should see "Login!"