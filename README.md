# PPV framework parser for WMSPanel

[![CI](https://github.com/kev-ac/wmspanel-ppv-parser/actions/workflows/ci.yml/badge.svg)](https://github.com/kev-ac/wmspanel-ppv-parser/actions/workflows/ci.yml)

This library presents functionality for the WMSPanel PPV framework.

Pass the JSON payload of the media servers in and receive structured data of your viewership back.<br>
The library is able to validate requests by passing your PPV token.

You can also generate media signatures for playback.

## Installation

Require the library with composer:

`composer require kev-ac/wmspanel-ppv-parser`


## Usage

Instantiate the main class without validation:

`$parser = new KevAc\WmsPanel\PpvParser\PpvParser();`

Instantiate the main class with validation:

`$parser = new KevAc\WmsPanel\PpvParser\PpvParser("YOURTOKEN", true);`

Receive structured data:

`$data = $parser->parse($yourPpvPayloadAsJsonString);`

Generate response with DenyList (and Solution if token is specified above):

`$response = $parser->generateResponse($arrayWithDeniedIds, $yourPpvPayloadAsJsonString);`

Generate media signature for playback

With client IP:<br>
`$playbackUrl = MediaSignature::createForUrl("YOUR_PLAYBACK_URL", "YOUR_KEY", "YOUR_USER_ID", 20);`<br>
<i>The last parameters specifies the duration the url is valid in minutes.</i>

Without client IP:<br>
`$playbackUrl = MediaSignature::createForUrl("YOUR_PLAYBACK_URL", "YOUR_KEY", "YOUR_USER_ID", 20, "127.0.0.1");`<br>

## Data structure

All types of data are wrapped in entities with getter methods.<br>
The main level of the resulting data is an array with VHost entities. Usually there is only one VHost entity but could be more if you have more than one domain name for one single server.

Below each VHost are the Application, Stream and Player data.<br>
For ease of use Player information is also exposed on Application level.

## Sample response
You'll find a sample structured response here:
[sample-response.txt](sample-response.txt)


### License

This library is licensed under GNU General Public License v3.0.
