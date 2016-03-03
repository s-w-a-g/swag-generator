# Sw:ag − Static Website : Another Generator

Official documentation website: https://s-w-a-g.github.io/

## Generating the website

Once the directory is ready and respects the structure specs, run
```shell
$ ./swag generate /Path/To/Your/Directory
```

where source is the user folder containing the pages and data folders.

By default, the processed pages will be placed in `static_website` straight in the user directory.
To change the destination directory run the Sw:ag Generator with the `--destination` option:
```shell
$ ./swag generate /Path/To/Your/Directory --destination=.
```

## Running unit tests

```shell
$ vendor/bin/phpunit
```
