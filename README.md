# Laravel-Resx-Converter
Convert your .NET resource files to Laravel language files

## Instalation

The first step is to install the package through `Composer`, for that require `"itx-technologies/artisan-resx-to-lang"` in your `composer.json` file
```
  "require-dev": {
    "way/generators": "~2.0"
  }
```

Then update composer from the command line

```
	composer update --dev
```

And finally add the following to the `providers` array of the `/app/config/app.php` file

```
	'ItxTechnologies\ArtisanResxToLang\ArtisanResxToLangServiceProvider',
```

You can then call `php artisan` to see the installed resxToLang command

## Usage

First of all, make sure that the folders for every language you are trying to convert are present in your `/app/lang` directory.
Then, make sure that the base language, the one in the flat [name].resx files, is set as your fallback locale in `/app/config/app.php`. 
Finally, transfer your .resx files in a folder called `resx` in your `public` folder. When that is done, simply call the command from the command line:

```
	php artisan resxToLang [filename]
```

When entering the filename, don't add the `.resx`.

The command will do it's magic, once it's finished, you can open your `app/lang/[locale]` folder and see a file named `[filename].php` that includes every string from your original resx file. In the other locale folders, you will see the same php file containing the same strings in that language.

## Translating locale lines from C# to Laravel

if you used the razor templating engine in your original C# project, the translation is really fast. A line that looked like this:

```
	@Resources.File.String
```
becomes:
```
	@lang('file.String')
```
in a blade file or:
```
	echo Lang::get('file.String');
```
in a php block. 

Please note that the file names are all lowercased during the conversion but not the string name
