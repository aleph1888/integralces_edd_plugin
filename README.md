integralCES_edd_plugin
==================

EasyDigitalDownloads integralCES gateway (100% compatible with Campaignify)


Workflow
----
From *COOP number* posting on wordpress action *edd_integralCES_gateway_cc_form*(1), integralCES API v0(2) through openTransact(3)(a) to charge payment. 
(1) http://pippinsplugins.com/create-custom-payment-gateway-for-easy-digital-downloads/
(2) http://www.integralces.net/doc/developer
(3) http://www.opentransact.org/usecases.html
(a) OpenTransact aims to create the equivalent of the HTTP standard for financial transactions. It is based on established well known HTTP, REST and OAUTH standards. You can use the same standard and implementation code to handle everything from currency payments to loan issuance and stock trades. 

Agents
-----
- integralCES: social currencies management for communities.

Configuration
--------------
1) Set gateway default values on *Campaings - Settings - Gateways*.

- Fields information:
    * Client_id, Password => talk to integralCES(1) for Client Registration Script
    * Temp => *integralCES API v0* will write there temp files file procesing payments.    
    * API_url => http://drupalcode.org/integralCES/XXXXX.git/tree/9x9x9x9x9    
    * API_url_sandbox => http://drupalcode.org/sandbox/esteve/1367140.git/tree/3a41dcb
    * One or multiple CESid => Allowed values are *ONE* and *MULTIPLE* meaning whether each campaign has its own CESid or if every transfer goes to your platform CESid.
    * Transfer info => Message that will be displayed in checkout section previous to purchase. Here you can inform to your user that an email with instructions will be send.
    * FROM / SUBJECT / BODY => This is instructions email.

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

