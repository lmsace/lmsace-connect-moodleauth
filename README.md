# LMSACE Connect SSO - Moodle Authentication Plugin

LMSACE Connect SSO is an authentication type plugin for Moodle that allows users to sign up and sign in to Moodle using a single sign-on (SSO) feature. This plugin also enables users to sign in to WordPress using their Moodle credentials.

## Configuration

After the installation of the LMSACE Connect SSO plugin, it needs to be enabled in the Moodle LMS. Here's how you can enable SSO using the LMSACE Connect SSO authentication plugin:

1. Go to '*Site Administration*' -> '*Plugins*' -> '*Authentication*' -> '*Manage authentication*'.
2. In the "*Available authentication plugins*" table, find the plugin "**LMSACE Connect SSO**" and click the Eye icon to enable SSO.


## Documenation

The official documentation to setup connections and SSO between Moodle and Woocommerce using LMSACE Connect can be found at https://github.com/lmsace/lmsace-connect-woocommerce/wiki


### Version

    * Plugin version: 1.0
    * Released on: 28 April 2023
    * Authors: https://lmsace.com, LMSACE Dev Team


### Git Repository

LMSACE Connect Moodle auth - [https://github.com/lmsace/lmsace-connect-moodleauth](https://github.com/lmsace/lmsace-connect-moodleauth)


### Installation steps using ZIP file

> Before installing this LMSACE Connect SSO plugin, Please install the [lmsace-connect-moodle](https://github.com/lmsace/lmsace-connect-moodle) plugin and install the wordpress plugin [lmsace-connect-woocommerce](https://github.com/lmsace/lmsace-connect-woocommerce)

1. Download the latest version of LMSACE Connect Moodleauth plugin from the [github releases](https://github.com/lmsace/lmsace-connect-moodleauth/releases/tag/v1.0)
2. Next login as Site administrator
3. Go to '*Site Administration*' -> '*Plugins*' -> '*Upload Plugin*', On here upload the downloaded plugin zip '**auth_lmsace_connect_v1.0.zip**'.
4. Once the plugin validation completed, click the "*continue*" button.
5. On ‘Plugins check’ page you will see the lmsace_connect auth plugin in listing, Click the “Upgrade Moodle database now” button displayed on bottom of the page

> You will get success message once the plugin installed successfully.

6. By clicking “Continue” button on success page. you will redirect to the admin notification page.


### Installation steps using Git

1. Clone the LMSACE Connect Moodle Auth plugin Git repository into the folder '*auth*'.
2. Rename the folder name into '**lmsace_connect**'.
3. Go to ‘Site administration’ -> ‘Notifications’ , here on ‘Plugins check’ page you will see the "*lmsace_connect*" auth plugin in listing.
4. Click the “Upgrade Moodle database now” button displayed on bottom of the page.

> You will get success message once the plugin installed successfully.

5. By clicking “Continue” button on success page. You will redirect to the admin notification page.

### Support

If you need any assistance with the LMSACE Connect SSO plugin, please contact LMSACE support at info@lmsace.com.
