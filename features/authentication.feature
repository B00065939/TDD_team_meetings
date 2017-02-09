Feature: Authentication
  In order to gain access to the user management area
  As an admin
  I need to be able to login and logout

@javascript
  Scenario: Login in to management page
    Given I am on "/"
    When I fill in "login" with "admin"
    And I fill in "password" with "pass"
    And I press "login_submit"
    Then I should see "Welcome Admin"