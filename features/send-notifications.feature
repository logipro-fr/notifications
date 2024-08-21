Feature: Send web push notifications to subscribers
As a publisher
I want to send web push notifications to subscribers
To inform them of updates, special offers and important alerts

    Scenario: Successful push notification to all subscribers
        When a new article is published on the site
        Then a push notification entitled "Accident prediction" with the body "A prediction of an accident is performed on one of your monitoring points" and the icon "icon_accident.png" is sent to "Jean" and "Amina"
        And the notification contains the URL "https://accidentprediction.fr" for redirection

    Scenario: Failed push notification to subscriber
        Given the token of "Frédéric" has expired
        When a push notification is attempted to be sent to "Frédéric"
        Then the notification fails
        And the publisher tries to refresh the token of "Frédéric" or mark it as inactive

    Scenario: Limitation of frequency of notifications
        Given "Jean" received a notification less than an hour ago
        When a new trigger event is recorded
        Then the sending of the new notification to "Jean" is delayed to respect the frequency limitation
        And the notification will be sent to "Jean" after a time period respecting his frequency limitation preference

    Scenario: Execute an action following the receipt of a notification
        Given "Jean" received a notification
        And the payload contains some actions 
        When "Jean" clicks the "More info" notification button
        Then the page https://accidentprediction.fr/accidents/RN88 is opened