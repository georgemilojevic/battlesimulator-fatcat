# battlesimulator-fatcat

App is made with Symfony framework

Download the repo to your local machine and inside the folder run ```composer install```

Create mysql db and update the name in .env file more specifically on the line where it says 

```DATABASE_URL=mysql://root:@127.0.0.1:3306/YOUR_DATABASE_NAME?serverVersion=5.7```

Run migrations with ```bin/console doctrine:migrations:migrate```

Start the local server with ```bin/console server:run```

Go to browser and start the application
