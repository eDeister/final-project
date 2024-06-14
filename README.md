# Phrygian
A music eCommerce website where customers can view various instruments, add them to a cart, place an order, and view their past orders. Admins have various controls available to them that users do not. Currently, Admins can only remove listings from the database, but there is code in the works to allow them to add a listing and update a listing. Customers are able to search for the name of an instrument or sort by price. 

https://edeister.greenriverdev.com/328_2/final-project/

Admin login:
test@phrygian.com
This1!test

# Authors
Ethan Deister
Eugene Faison
Abdul Rahmani

# Requirements
== Separates all database/business logic using the MVC pattern.


final-project/model/data-layer.php  - Model
final-project/views/.  - Views
final-project/controllers/controller.php - Controller

The project maintains a consistent pattern of separating routing logic from the index page via the controller, database logic from the controller via the model, and HTML from the controller via the views.

== Routes all URLs and leverages a templating language using the Fat-Free framework.

final-project/index.php - All accessible URLs
final-project/controllers/controller.php - All accessible Routes
final-project/views/. - All templating

The project routes all URLs via the controller and uses templating across the board - no PHP echoes, no JavaScript prints.

== Has a clearly defined database layer using PDO and prepared statements to prevent SQL injection.

final-project/model/data-layer.php

The data layer uses a PDO connection to prepare all SQL queries with placeholders to prevent injection attacks.

== Data can be viewed and added.

final-project/views/checkout.html
final-project/views/orders.html
final-project/controllers/controller.php ('GET /checkout', 'GET /orders')

A customer is able to add instrument listings to their cart, view them, place an order, and view all their previous orders.

== Uses OOP, and defines multiple classes, including at least one inheritance relationship.

final-project/classes/user.php 
    ->
      final-project/classes/customer.php
      final-project/classes/admin.php

Multiple classes are defined, including an inheritance relationship in which a customer extends from a user, and an admin extends from a user. Customers and Admins have different functions and privileges.

== Contains full Docblocks for all PHP files and follows PEAR standards.

final-project/controllers/controller.php
final-project/model/data-layer.php
final-project/classes/.

All PHP classes and files contain extensive PHP Docblocks and follow basic PEAR standards.

== Has full validation on the server side through PHP. All client-side validation is removed for testing purposes.

final-project/controllers/controller.php
final-project/views/login.html
final-project/views/signup.html

Logging in and signing up both contain logic in the controller and the model for server-side data validation. Server-side validation results are sent back to the user via templating.

== All code is clean, clear, and well-commented. DRY (Don't Repeat Yourself) is practiced.

final-project/model/data-layer.php
final-project/views/includes/.

Several SQL queries are made dynamic to maintain DRY standards.
Several HTML templates are made dynamic to maintain DRY standards.
Comments made throughout all decision logic for clarity.
Unused/WIP code is moved towards the bottom of the page.

== Your submission shows adequate effort for a final project in a full-stack web development course.

final-project/views/signup.html 
  ->
    final-project/views/login.html
      ->
        final-project/views/search.html
          ->
            final-project/views/checkout.html
              ->    
                final-project/views/orders.html

There is a cohesive user experience from start to finish, incorporating all web development patterns we learned this quarter, and more (jQuery). There is adequate effort for this final project.

== GitHub repo includes readme file outlining how each requirement was met; UML diagram; and ER diagram
== Has a history of commits from both team members to a Git repository. Commits are clearly commented.
# UML Diagram:
![image](https://github.com/eDeister/final-project/assets/104542550/d141b8d8-c9f7-4e8a-891d-490f5ccda74b)
# ER Diagram:
![image](https://github.com/eDeister/final-project/assets/104542550/c6a52697-10cd-4c46-b11a-d597e79d6d13)
# Github Contributions
![image](https://github.com/eDeister/final-project/assets/104542550/80b6c0f4-4de7-4da3-b4da-5017507d756f)
