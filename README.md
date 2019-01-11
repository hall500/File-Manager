# KingQuick
A lightweight, simple to use PHP framework with CLI

Get started with MVC quickly without having to bother much about the underlying code base.

# USAGE
Rename the file htaccess to .htaccess

![image of mvc framework](https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/ModelViewControllerDiagram2.svg/1280px-ModelViewControllerDiagram2.svg.png)

0. Edit the config.ini file and you are good to go.

1. Create a folder named after the page being created. 
In the created folder always start with an 'index.php' file
For example: to create a 'home' page.
Goto views folder and create a folder called home
Inside the folder create an index.php file
Note: This structure allows you to create subsections for the created page.

2. Create a Controller File named after the created file
For Example: for the 'home' page you created. You can create a corresponding controller
in the controllers folder called 'Home.php'
Note: Don't forget to edit the Name of the class to the appropriate controller Name

3. The base folder contains all files neccessary for the application to work which are neccesary for initializing the application

4. The models folder contains all logic that runs on the application
For example: A mail sender functionality could be saved as 'EmailSenderModel.php'

5. The models class can extend Model Class Granting easy access to database manipulations

6. The web folder contains all public files which are accessible to everyone such as the css, js, fonts, and images.

7. The views contains all pages for the application.



A lightweight PHP MVC framework for creating and deploying web applications quickly. It uses minimal php code and is easy to understand for basic beginners. Also has CLI integrated for creating views, models, controllers and widgets. 

Controller functions handle routing to pages as specified

## KingQuick CLI (KQCLI)
#### All cli commands start with "php index.php parameters" accessed from root/web
To get started with KQCLI enter "cd web" in command line at the root folder of your app
To create a new model
parameters: -m ModelName   or -model ModelName
example: "php index.php -m Users"
To create a new Controller
parameters: -c ControllerName or -controller ControllerName
example: "php index.php -c Todos"
To create a new View
parameters: -v ViewName or -view ViewName
example: "php index.php -v todos"
To create a new widget
parameters: -w WidgetName or -widget WidgetName
example: "php index.php -v alert"
To create a controller and view together
parameters: -q Name or -quick Name
example: "php index.php -v Todos"