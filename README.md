[Portuguese Version](https://github.com/skay1994/portifolio-my-framework/blob/master/README_PT.md)

# My Framework

A project created for study and portfolio, based on other PHP frameworks and their strategies and resources.

## Objective

This is a project created for study, so I can visualize, analyze, and rebuild functionalities of other frameworks. 

Enhance my understanding of how the PHP language works and, along the way, improve the products and systems I develop.

I'm using GitHub Projects for task management: [Project](https://github.com/users/skay1994/projects/1/views/1)

## Influences

The initial idea is a replica of Laravel, which is the framework I have the most understanding and mastery of.

However, it doesn't exclude the possibility of including features from other existing frameworks.

## Requirements

The system is being built with PHP 8.3. I'll be upgrading the minimum version based on the new PHP releases.

## Features

Features are being developed over time.

 - [x] Service Container: The service container is already functional and tested, it's in an initial state with the following features:
   - [x] Class Resolver: The container will create an instance of the class and return the instance.
   - [x] Constructor Parameter: If the class has parameters in the constructor, they will be resolved/injected, if they are other classes, the container will repeat the process to return the instance to the constructor of the initial class.
   - [x] Bindings: Creates a link to a string to an instance of a class/function/others, for easy retrieval from anywhere in the application.

 - [x] Facades: These are classes to facilitate access to other classes, acting as shortcuts.

 - [x] Routes: A routing system using PHP 8 attributes
   - [x] Exact Routes
   - [x] Routes with parameters
   - [ ] Route Names
   - [x] Routes with multiple HTTP methods
   - [ ] Route Groups

 - [ ] Database
   - [ ] QueryBuilder
   - [ ] ORM

## Tests

The code is being developed with tests via PestPHP.

##  Applications

Applications made with this framework:

 - Base Application: [my-application](https://github.com/skay1994/portifolio-my-application)