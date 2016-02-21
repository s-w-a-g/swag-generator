# Sw:ag − Static Website : a Generator

Sw:ag is an easy to use Static Website Generator. Following a simple directory structure for your templates and data, it will generate your pages ready to deploy to your server.

## Structure? What Structure? You said it was easy…

The generator needs a directory with your assets to generate the website. It must contain 2 subdirectories, __pages__ and __data__.

### `pages` directory

- Any Twig file beginning with a _meta_ comment will be processed and added to the generated website.
- Twig not having the _meta_ comment will be ignored, allowing Twig dependencies and inclusion.
- Any other files will simply be duplicated in the generated website directory, respecting the user directory structure.

### `data` directory

A data variable is built upon the `data` directory structure. This variable is passed along to the processed Twig files.

Currently supported
- Yaml files
- Markdown files with header

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
