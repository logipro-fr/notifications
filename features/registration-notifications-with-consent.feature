Feature: Registration for push notifications with consent

    Scenario: Subscription at a website notification publisher
        Given a website notification publisher propose a user to subscribe to receive notification 
        When the user accepts to subscribe
        Then the navigator on the device become a new subscriber of the publisher
        And the navigator has a token that allows to recogize it

    Scenario: Refusal to register for push notifications
        Given a website notification publisher propose a user to subscribe to receive notification 
        When the user refuse to subscribe
        Then the navigator on the device don't become a new subscriber of the publisher
        And the navigator hasn't a token that allows to recogize it

    Scenario: Unsubscription at a website notification publisher
        Given a website notification publisher propose a user to subscribe to receive notification 
        When the user want to unsubscribe
        Then the navigator unsubscribed from publisher
        And the navigator deleted the token that allows to recogize it

    Scenario: Silent subscription at a website notification publisher
        Given a website notification publisher propose a user to subscribe to receive notification 
        When the user complete an action (for exemple a purchase)
        Then the navigator receives an invitation to subscribe for the publisher
        And if the user accept, the navigator has a token that allows to recogize it

    