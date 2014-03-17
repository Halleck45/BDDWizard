Feature: Incomplete
  In order to anything
  As as anything
  I should be able to do anything


  Scenario: never-tested - correct scenario 1
    Given anything true
    When anything true
    Then anything true

  Scenario: never-tested - incorrect scenario 2
    Given anything true
    Given anything not defined
    When anything true
    Then anything true

  Scenario: never-tested - incorrect scenario 3
    Given anything true
    When anything not defined
    When anything true
    Then anything true

  Scenario: never-tested - correct scenario 4
    Given anything not defined
    When anything not defined
    Then anything true
    Then anything true
