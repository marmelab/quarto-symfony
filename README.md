# quarto-symfony
Quarto game server using symfony

## Install

Install docker image

```
make install
```

## Init-db

Mount database

```
make init-db
```

## Run webserver

Start the web server at http://localhost

```
make run
```

## Stop webserver

Stop the web server

```
make stop
```

## Test

Runs all the tests of the project

```
make test
```

## Deploy

Deploy project on server
  -Add ssh parameter to specify distant connection name (like make deploy sshname=quartoServer)

```
make deploy
```
