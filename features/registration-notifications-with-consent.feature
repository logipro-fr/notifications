Feature: Registration for push notifications with consent

    Scenario: Subscription at a website notification publisher
        Given a website notification publisher propose a user to subscribe to receive notification 
        When the user accepts to subscribe
        Then the navigator on the device become a new subscriber of the publisher
        And the navigator has a token that allows to recogize it

    Scenario: Refusal to register for push notifications
        Given a website notification publisher propose a user to subscribe to receive notification 
        When the user refuse to subscribe
        Then nothing happens

    Scenario: Unsubscription at a website notification publisher
        Given a website notification publisher propose a user to subscribe to receive notification 
        When the user want to unsubscribe
        Then the navigator unsubscribed from publisher
        And the navigator deleted the token that allows to recogize it