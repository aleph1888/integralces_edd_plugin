integralCES_edd_plugin
==================

EasyDigitalDownloads integralCES gateway (100% compatible with Campaignify)


Workflow
----
From *COOP number* input in *edd:cc_form integralCES*, integralCES API v0 oAUTH token validation. 

Agents
-----
- integralCES: social currencies management for communities.

Configuration
--------------
1) Set gateway default values on *Campaings - Settings - Gateways*.

- Fields information:
    * Client_id, Password => talk to integralCES(1) for Client Registration Script
(1) http://www.integralces.net/contact
    * Temp => integralCES API v0 will write there temp files file procesing payments.
    * API_url => http://drupalcode.org/integralCES/XXXXX.git/tree/9x9x9x9x9
    * API_url_sandbox => http://drupalcode.org/sandbox/esteve/1367140.git/tree/3a41dcb
    

Version
----

0.0

Tech
-----------

*integralCES_EDD_gateway* uses a number of open source projects to work properly:
* [Wordpress.org] - https://github.com/WordPress/WordPress
* [Easy-Digital-Downloads] - https://github.com/easydigitaldownloads/Easy-Digital-Downloads
* [Astoundify / crowdfunding] - https://github.com/Astoundify/crowdfunding
* [integralCES] - http://www.integralces.net/
* [Opentransact] - http://www.opentransact.org/


Dependencies
--------------
- integralCES API v0 dependencies
    * Curl
	* Openssl


##### Calaways EDD gateways bundle. Configure Plugins. Instructions in following README.md files

* plugins/github/README.md


License
----

?


**Free Software, Hell Yeah!**
@BTC 1Evy47MqD82HGx6n1KHkHwBgCwbsbQQT8m

