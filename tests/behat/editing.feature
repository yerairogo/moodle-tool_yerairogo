@tool @tool_yerairogo
Feature: Creating, editing and deleting an entry
  In order to manage entries in the tool_yerairogo plugin
  As a teacher
  I want to be able to create, edit, and delete entries

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | John      | Doe      | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | format |
      | Course 1 | C1        | weeks  |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
    When I am on the "Course 1" course page logged in as teacher1

  @javascript
  Scenario: Add a new entry
    Given I am on "Course 1" course homepage
    When I navigate to "My first Moodle plugin" in current page administration
    And I follow "Add entry"
    And I set the following fields to these values:
      | Name      | Test Entry |
      | Completed | 1          |
    And I press "Save changes"
    Then the following should exist in the "tool_yerairogo_overview" table:
      | Name       | Completed |
      | Test Entry | Yes       |

  @javascript
  Scenario: Edit an existing entry
    Given I am on "Course 1" course homepage
    When I navigate to "My first Moodle plugin" in current page administration
    And I follow "Add entry"
    And I set the following fields to these values:
      | Name      | Test Entry |
      | Completed | 0          |
    And I press "Save changes"
    And I click on "Edit" "link" in the "Test Entry" "table_row"
    And I set the following fields to these values:
      | Name      | Updated Entry |
      | Completed | 1             |
    And I press "Save changes"
    Then the following should exist in the "tool_yerairogo_overview" table:
      | Name          | Completed |
      | Updated Entry | Yes       |

  @javascript
  Scenario: Delete an entry
    Given I am on "Course 1" course homepage
    When I navigate to "My first Moodle plugin" in current page administration
    And I follow "Add entry"
    And I set the following fields to these values:
      | Name      | Test Entry |
      | Completed | 1          |
    And I press "Save changes"
    And I click on "Delete" "link" in the "Test Entry" "table_row"
    Then I should not see "Test Entry"
