# PPV framework parser for WMSPanel

This library presents a parser service for the WMSPanel product.

Pass the JSON payload of the media servers in and receive structured data of your viewership back.

The library is able to validate requests by passing your PPV token.


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

## Data structure

All types of data are wrapped in entities with getter methods.<br>
The main level of the resulting data is an array with VHost entities. Usually there is only one VHost entity but could be more if you have more than one domain name for one single server.

Below each VHost are the Application, Stream and Player data.<br>
For ease of use Player information is also exposed on Application level.


### License

This library is licensed under GNU General Public License v3.0.
