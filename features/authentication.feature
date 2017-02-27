Feature: Authentication
  In order to gain access to the user management area
  As an admin
  I need to be able to login and logout

//@javascript
  Scenario: Login in to management page
    Given I am on "/"
    When I fill in "Username" with "admin2@wp.pl"
    And I fill in "Password" with "pass"
    And I press "Login"
    Then I should see "Administrator Panel"
    Then I should see "Logout"

  Scenario: Logout from administrator panel
    Given I am on "/admin/adminpanel"
    Then I follow "logout"
#    Then print last response
    When I follow "Logout"
    Then I should see "Login!"