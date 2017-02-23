Feature: Authentication
  In order to gain access to the user management area
  As an admin
  I need to be able to login and logout

//@javascript
  Scenario: Login in to management page
    Given I am on "/"
    When I fill in Username with "admin@wp.pl"
    And I fill in "Password" with "pass"
    And I press "Login"
    Then I should see "Welcome Admin"