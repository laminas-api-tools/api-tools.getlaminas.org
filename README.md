# Laminas API Tools website (api-tools.getlaminas.org)

## Docker

For development, we use [docker-compose](https://docs.docker.com/compose/);
make sure you have both that and Docker installed on your machine.

Build the images:

```console
$ docker-compose build
```

And then launch them:

```console
$ docker-compose up -d
```

You can then browse to `http://localhost:8080`, and any changes you make in the
project will be reflected immediately.

## CSS and JavaScript

CSS can be found in the `asset/scss/` directory (we use SASS for defining our CSS),
and JS can be found in the `asset/js/` directory.

After changing CSS or JS you need rebuild assets, as following:

```bash
$ cd asset
$ npm install
$ gulp
```

The above commands are run automatically when you execute `composer build`.

**You will need to build the assets in order to preview pages!**

## Documentation

Documentation is written in a separate repository,
[laminas-api-tools/documentation](https://github.com/laminas-api-tools/documentation),
and the https://api-tools.getlaminas.org website consumes that documentation.
