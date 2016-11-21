# lastfm

[![Software License][ico-license]](LICENSE.md)

This library wraps lastfm's API for usage in any php framework via composer.
Supported APIs: 

```
geo.getTopArtists
artist.getTopTracks
```

## Install

Via Composer

``` bash
# Clone this repo from github

# Have to make sure you have defined the repository for this, since this is not in packagist yet

"repositories": [
        {
            "type": "path",
            "url": "../lastfmphpapi"
        }
    ],
# (Add this above to your composer file first). url : based on your files location


Add composer require:

"require": {
        "mazharul/lastfm" : "dev-master"

    },

# Install composer dependencies
composer install
```

## Usage

``` php
$lastfm = new mazharul\lastfm($api_key);
$lastfm->getGeoTopArtists($country);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

No tests yet :(
``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email me@mazharulanwar.com instead of using the issue tracker.

## Credits

- [Maz][link-author]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[link-author]: https://github.com/mazharul 
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square

