integralCES_edd_plugin
==================

EasyDigitalDownloads integralCES gateway (97% compatible with Campaignify)


Workflow
----
From *COOP number* posting on wordpress action *edd_integralCES_gateway_cc_form*(1), integralCES API v0(2) through ICES interop Server(3)


(1) http://pippinsplugins.com/create-custom-payment-gateway-for-easy-digital-downloads/

(2) https://github.com/aleph1888/integralCES_consumer

(3) https://github.com/aleph1888/integralCES_interop

Agents
-----
- integralCES: social currencies management for communities. http://www.integralces.net/doc/developer

Configuration
--------------
1) Set gateway default values on *Campaings - Settings - Gateways*.

- Fields information:
    * Client_id, Password => talk to integralCES(1) for Client Registration Script

(1) http://www.integralces.net/contact
    

Version
----

0.0

Tech
-----------

*integralCES_EDD_gateway* uses a number of open source projects to work properly:
* [Wordpress.org] - https://github.com/WordPress/WordPress
* [Easy-Digital-Downloads] - https://github.com/easydigitaldownloads/Easy-Digital-Downloads
* [Astoundify / crowdfunding] - https://github.com/Astoundify/crowdfunding
* [Community Exchange System] - http://www.ces.org.za/
* [integralCES] - http://www.integralces.net/

Dependencies
--------------
- integralCES SDK v0 dependencies
    * Curl
	* Openssl
	* OAuth 1.0

##### Calaways EDD gateways bundle. Configure Plugins. Instructions in following README.md files

* plugins/github/README.md


License
----

?


**Free Software, Hell Yeah!**
@BTC 1DNxbBeExzv7JvXgL6Up5BSUvuY4gE8q4A

