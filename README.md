# Space Trader client / automation

This repository contains the code for a SpaceTrader client (with some automations).  
Learn more about this game here: https://spacetraders.io/

## Getting Started

This project use:

- PHP 8.3
- Symfony 7.0
- RabbitMQ
- Redis
- PostgreSQL
- Node 20
- VueJS 3
- Tailwind (with DaisyUI)



### Installing

These instructions will get you a copy of the project up and running on your local machine.

#### Setup Docker

You will need Docker to run this project.
Follow the Docker installation guide (https://docs.docker.com/get-docker/) to have it on your environment.

In folder `.docker/php-cli`
```
docker build -t spacetrader-cli:0.5 .
``````
In Folder `.docker/php-fpm`
```
docker build -t spacetrader-fpm:0.5 .
```

#### Setup the project


Add `spacetrader.local` to your `/etc/hosts`
```
10.20.0.2       spacetrader.local
```

Install PHP vendor 
```
docker compose run --rm php-cli composer install
```

Install JS deps (without polluting your local with node)
```
docker run -v $PWD:/var/www -it node:latest /bin/sh
cd /var/www
npm ci
npm run build
```
You can also `npm run dev` to watch assets files (it will rebuild automatically)

Launch the docker
```
docker compose up -d
```

Run the migrations
```
docker compose run --rm php-musicall bin/console doctrine:migration:migrate
```

Register to the game API
```
curl --request POST \
 --url 'https://api.spacetraders.io/v2/register' \
 --header 'Content-Type: application/json' \
 --data '{
    "symbol": "INSERT_CALLSIGN_HERE",
    "faction": "COSMIC"
   }'
   
```

Copy the token in your .env.local file at `TOKEN_API`


Go to `http://spacetrader.local` and enjoy !

### Todo 

- [ ] Add missing part of the game
- [ ] Add mercure
- [ ] Complete automation 
- [ ] Improve the user interface
- [ ] and more things !
