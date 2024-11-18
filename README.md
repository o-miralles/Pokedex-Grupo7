# Pokedex Project

This project is a simple web application where you can find your favorite pokemon and add it to your pokedex. It was created to be analyzed by SonarQube and OWASP ZAP using a Jenkins pipeline.

The application is written in PHP and uses a MySQL database to store the users and the pokemons. The frontend is written in HTML, CSS and JavaScript.

The Jenkins pipeline is responsible for building the application, run the SonarQube analysis, deploy the application to the web server and run the OWASP ZAP analysis.

The SonarQube analysis provides a report about the code quality, security and bugs. The OWASP ZAP analysis provides a report about the web application security vulnerabilities.

The application is also integrated with GitHub, so every 5 minutes the Jenkins pipeline is triggered and the application is rebuilt and redeployed.


When someone clones the repository they will need to do a "cp .env.example .env" and then put the data for the database connection