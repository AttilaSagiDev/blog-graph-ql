# **Magento 2 Blog GraphQL Extension** #

## Description ##

This extension will add GraphQL support to the Blog Magento 2 module. Adding support to get post by ID or post list via GraphQL Query.

## Features ##

- Adding support to receive a post by ID via GraphQL query
- Adding support to receive a list of posts via GraphQL query

It is a separate module that does not change the default Magento files.

Support:
- Magento Community Edition  2.4.x

- Adobe Commerce 2.4.x

## Installation ##

** Important! Always install and test the extension in your development environment, and not on your live or production server. **

1. Backup Your Data
   Backup your store database and whole Magento 2 directory.

2. Install Blog Magento 2 extension. Please see:
   https://github.com/AttilaSagiDev/blog/releases

3. Enable extension
   Please use the following commands in your Magento 2 console:

   ```
   bin/magento module:enable Space_BlogGraphQl

   bin/magento setup:upgrade 
   ```

## Change Log ##

Version 1.0.0 - May 11, 2024
- Compatibility with Magento Community Edition  2.4.x

- Compatibility with Adobe Commerce 2.4.x

## Support ##

If you have any questions about the extension, please contact with me.

## License ##

MIT License.
