Authentication server plugin platform  [![Build Status](https://travis-ci.org/jjhesk/v-server-sdk-bank.svg?branch=master)](https://travis-ci.org/jjhesk/v-server-sdk-bank)
=====================================

This is the large scale plugin moduel for coinapp server. 

As a part of authentication server to run on the vcoinapp. Authentication server will require cms server to communicate and deploy to the production server.

Plugin Name: Vcoin Server Authentication 2014
Plugin URI: https://github.com/jjhesk/authserver
Description: In order to run this module the server will need to activate the mentioned modules as list below: Titan Framework, WordPress Importer, Meta Box, JSON API, JSON API Auth, Email Login, Gravity Forms
Version: 1.0
Author: Heskom,
Author URI:
License: GPLv3

##feature highlights
 - api authentications
 - application registrations
 - data bank for app key and app secret
 - creating account for each new registered app
 - adding coins on each testing account, beta account, and launched account
 - user coin managements

##setup instruction
1. download the package in zip
2. download and install plugins from the list:  
 * [Titan Framework](https://github.com/gambitph/Titan-Framework)
 * [WordPress Importer](https://github.com/wp-plugins/wordpress-importer)
 * [Meta Box](https://github.com/rilwis/meta-box)
 * [JSON API](https://github.com/dphiffer/wp-json-api)
 * [JSON API Auth](https://github.com/jjhesk/json-api-auth-Wordpress)
 * [Email Login](https://github.com/wp-plugins/wp-email-login)
 * [Gravity Forms](http://www.gravityforms.com/) full package
 * [WPML](http://wpml.org/zh-hans/) full package
2. open and upload as a folder at the /app/wp-content/plugins
3. login the backend and enable module.

##API Document
https://docs.google.com/document/d/1ZJbHnUr7lj6lvds62Qcpu7FTZF-Oh61UGt6xLmT4st0/pub

##Thanks to previous packages
- https://github.com/bobthecow/mustache.php
