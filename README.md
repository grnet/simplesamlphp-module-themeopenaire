# simplesamlphp-module-theme-openaire

A theme for [SimpleSAMLphp](https://simplesamlphp.org/) based on Bootstrap.
## Installation

You can install the theme using any of the methods below.

### Composer

Add the following lines in the `composer.json` file that is located in your
SimpleSAMLphp installation:

If you want to use [composer](https://getcomposer.org/) to install this theme
you need to edit `composer.json` file that is located in your SimpleSAMLphp
installation. Check the following example, that includes all the necessary
additions for the installation of the **simplesamlphp-module-themeopenaire v1.0.0**.

```
"require": {

    ...

    "grnet/simplesamlphp-module-themeopenaire": "1.0.0",
    "grnet/css": "1.0.0",
    "grnet/js": "1.0.0"
},
"repositories": [

    ...

    {
        "type": "vcs",
        "url": "https://github.com/grnet/simplesamlphp-module-themeopenaire"
    },
    {
      "type": "package",
      "package": {
        "name": "grnet/css",
        "version": "1.0.0",
     :w
   "dist": {
          "type": "zip",
          "url": "https://github.com/grnet/simplesamlphp-module-themeopenaire/releases/download/v1.0.0/css.zip"
        }
      }
    },
    {
      "type": "package",
      "package": {
        "name": "grnet/js",
        "version": "1.0.0",
        "dist": {
          "type": "zip",
          "url": "https://github.com/grnet/simplesamlphp-module-themeopenaire/releases/download/v1.0.0/js.zip"
        }
      }
    }
    ],
    "scripts": {

      ...

      "post-update-cmd": [
        "cp -r 'vendor/grnet/css' 'modules/themeopenaire/www/resources'",
        "cp -r 'vendor/grnet/js' 'modules/themeopenaire/www/resources'"
      ]
    },
```

With the above configuration composer will do several operations:
- It will put the module `themeopenaire` in the `modules` directory.
- It will download and extract the compressed `css` and `js` directories that
  include the minified css and javascript files.
- It will copy the `css` and `js` directories from the `vendor/grnet` directory
  in the `themeopenaire/www/resources` directory, where the static files of the
  theme should be placed.

### Direct download

You can download `themeopenaire.zip` from the [release page](https://github.com/grnet/simplesamlphp-module-themeopenaire/releases).
Download the zip file of the preferred release and extract its contents in the
`modules` directory of your SimpleSAMLphp installation.

### Clone repository

Clone this repository into the `modules` directory of your SimpleSAMLphp
installation as follows:
```
cd /path/to/simplesamlphp/modules
git clone https://github.com/grnet/simplesamlphp-module-themeopenaire.git themeopenaire
```
Note that the cloned repository will not include the css files or minified
javascript files.
You'll need to download or produce them. You can download the compressed
directories (`js.zip` and `css.zip`) from the [release page](https://github.com/grnet/simplesamlphp-module-themeopenaire/releases) and
extract them under `modules/themeopenaire/www/resources`.  If you want to produce
them, you may read the customisation instructions below.


## Configuration

### Basic usage

In order to use this module as theme you need to set in the
`config/config.php`: `'theme.use' => 'themeopenaire:ssp'`

## Customization

### Wording

You can find definitions and dictionaries in the `dictionaries` directory.

### Images

Place your logo and favicon in the directory:
`themeopenaire/www/resources/images` If you name them `logo.png` and
`favicon.png` they will be loaded without any other modification.  If you name
them differently you need to modify the template `header.php` that is placed in:
`themeopenaire/themes/ssp/default/includes/`.

### Footer
If you want to make any changes in the footer you need to modify the template
`footer.php` that is placed in: `themeopenaire/themes/ssp/default/includes/`.

### CSS

To produce the css files for this theme follow these steps:
- Install sass ([installation guide](http://sass-lang.com/install))
- Go to the directory `themeopenaire/www/resources`
- Run the cli sass: `sass --update sass:css`

After these steps the css files will be in the directory
`themeopenaire/www/resources/css`

You can change the settings of this theme from the files that are located in the
`sass` directory. After you change any of these files you need to produce the css file that the
browser will serve. You can do that by running: `sass --update sass:css`, as
mentioned above.

Please, check the help page of the cli tool sass if you want to use more
compiling options.


## About SimpleSAMLphp themes

You can read more about themes in a SimpleSAMLphp installation from the
[official documentation](https://simplesamlphp.org/docs/stable/simplesamlphp-theming).


## License

Licensed under the Apache 2.0 license, for details see `LICENSE`.
