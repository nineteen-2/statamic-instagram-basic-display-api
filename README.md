# Display a user Instagram Feed using Instagram Basic Display API

![Statamic 3.0+](https://img.shields.io/badge/Statamic-3.0+-FF269E?style=for-the-badge&link=https://statamic.com)

📸 This addon provide a robust way to integrate Instagram Feed using **Instagram Basic Display API**

🔥 Using the API is the recommanded way to acces Instagram User Feed 

⚡️ This addon also provide cache to limit API calls 

📦 It is based on the [Instagram Basic Display PHP API](https://github.com/espresso-dev/instagram-basic-display-php)

## Requirements

* PHP 7 or higher
* cURL
* Facebook Developer Account
* Facebook App

## Get started

To use the [Instagram Basic Display API](https://developers.facebook.com/docs/instagram-basic-display-api), you will need to register a Facebook app and configure Instagram Basic Display. 
Follow the [getting started guide](https://developers.facebook.com/docs/instagram-basic-display-api/getting-started).


## Installation

Require it using Composer.
```
composer require nineteensquared/statamic-instagram-basic-display-api
```

## Configuration

### Set the App ID and SECRET in the .env file

```
INSTAGRAM_APP_ID=
INSTAGRAM_APP_SECRET=
```

### Set the OAuth Redirect URI in Instagram Basic Display configuration

For example: ```https://statamic.test/cp/nineteen-ig/auth```

### Connect to Instagram

Log into the control panel, and go to **Tools** > **Instagram** 
and click on the **Login with Instagram** button. 

## Tag

```
{{ instagram limit="6" as='ig')}}
    <div>
        {{ ig }}
            <a href="{{ permalink }}" >
                <img src="{{ thumbnail_url ?? media_url }}" alt="{{ caption }}" />
            </a>
        {{ /ig }}
    </div>
{{ /instagram }}


```


## Parameters

| Parameter |Default Value | Description |
|-----------|--------------|-------------|
| `limit` |	`12` | Number of image |

## Variables

| Variable | Description |
|----------|-------------|
| `caption` | The Media's caption text. Not returnable for Media in albums. |
| `id` | The Media's ID. |
| `media_type` | The Media's type. Can be IMAGE, VIDEO, or CAROUSEL_ALBUM. |
| `media_url` | The Media's URL. |
| `permalink` | The Media's permanent URL. Will be omitted if the Media contains copyrighted material, or has been flagged for a copyright violation. |
| `thumbnail_url` | The Media's thumbnail image URL. Only available on VIDEO Media. |
| `timestamp` | The Media's publish date in ISO 8601 format. |
| `username` | The Media owner's username. |


## Overriding configuration

```php artisan vendor:publish --tag=instagram-config```           
 
## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.


## Common error :

```
{
"error_type": "OAuthException",
"code": 400,
"error_message": "Insufficient developer role"
}

```

➡️ Add an Instagram Test User [following step 3](https://developers.facebook.com/docs/instagram-basic-display-api/getting-started)


## Support

🐛 Open an issue [on github](https://github.com/nineteen-2/statamic-instagram-basic-display-api/issues)
