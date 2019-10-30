Feature: Retrieve administrators
  In order to manage administrators
  As a brand admin
  I need to be able to retrieve them through the API.

  @createSchema
  Scenario: Retrieve the administrators json list
    Given I add Brand Authorization header
     When I add "Accept" header equal to "application/json"
      And I send a "GET" request to "administrators"
     Then the response status code should be 200
      And the response should be in JSON
      And the header "Content-Type" should be equal to "application/json; charset=utf-8"
      And the JSON should be equal to:
    """
      [
          {
              "email": "test@irontec.com",
              "active": true,
              "name": "Admin Name",
              "lastname": "Admin Lastname",
              "id": 4
          }
      ]
    """

  Scenario: Retrieve certain administrator json
    Given I add Brand Authorization header
     When I add "Accept" header equal to "application/json"
      And I send a "GET" request to "administrators/4"
     Then the response status code should be 200
      And the response should be in JSON
      And the header "Content-Type" should be equal to "application/json; charset=utf-8"
      And the JSON should be like:
    """
      {
          "username": "test_company_admin",
          "pass": "****",
          "email": "test@irontec.com",
          "active": true,
          "name": "Admin Name",
          "lastname": "Admin Lastname",
          "id": 4,
          "company": "~",
          "timezone": {
              "tz": "Europe/Madrid",
              "comment": "mainland",
              "id": 145,
              "label": {
                  "en": "en",
                  "es": "es"
              },
              "country": 68
          }
      }
    """
