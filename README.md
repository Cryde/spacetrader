# Space Trader client / automation

Code is a bit messy in some parts  
Lots of todo 


Improve this readme !! 


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


Install 

In folder `.docker/php-cli`
```
docker build -t spacetrader-cli:0.1 .

In Folder `.docker/php-fpm`
``````
docker build -t spacetrader-fpm:0.1 .
```
In file /etc/hosts 
```
10.20.0.2       spacetrader.local


Composer install 
```
docker compose run --rm php-cli composer install
```

npm install ( without polluting your local with node :D )
```
docker run -v $PWD:/var/www -it node:latest /bin/sh
cd /var/www
npm ci
npm run build
```

Start to play
```
docker compose up -d
```

Go to `spacetrader.local/api` and enjoy !
