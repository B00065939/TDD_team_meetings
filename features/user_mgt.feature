Feature: User Management
  In order to maintain the users using this site
  As an admin
  I need to be able to add/edit/delete users

  Background:
    Given I am logged in as an admin
#@javascript
Scenario: Displaying user list
  Given There are 5 users in database
  And I am on "/admin/adminpanel"
  Then I should see list of users with at least 6 rows