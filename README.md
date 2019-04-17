<table>
        <tr>
            <td><img width="120" src="https://cdnjs.cloudflare.com/ajax/libs/octicons/8.5.0/svg/rocket.svg" alt="onboarding" /></td>
            <td><strong>Archived Repository</strong><br />
            The code of this repository was written during a <a href="https://marmelab.com/blog/2018/09/05/agile-integration.html">Marmelab agile integration</a>. It illustrates the efforts of a new hiree, who had to implement a board game in several languages and platforms as part of his initial learning. Some of these efforts end up in failure, but failure is part of our learning process, so the code remains publicly visible.<br />
        <strong>This code is not intended to be used in production, and is not maintained.</strong>
        </td>
        </tr>
</table>

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
