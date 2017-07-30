# PHP Tools

This package provides some useful tools for PHP development.

## Installation

To install, either run

``` bash
$ composer require bnjns/web-dev-tools
```

or add the following to the `require` section of your `composer.json` file:

```
"bnjns/web-dev-tools": "dev-master"
```

## Available Tools

### PHP

* Authentication
	* `Auth\LaravelStatusedUserProvider` - a custom UserProvider for Laravel 5 that allows user accounts to be disabled.
* HTML Services
	* `Html\FormBuilder` - a custom FormBuilder for Laravel 5 that includes some custom form inputs.
* Service Providers
	* `Providers\BladeServiceProvider` - a service provider for Laravel 5 that defines some custom Blade directives.
	* `Providers\HtmlServiceProvider` - a service provider for Laravel 5 that registers the custom `FormBuilder` class.
	* `Providers\UserProviderServiceProvider` - a service provider for Laravel 5 that registers the custom `UserProvider`.
	* `Providers\ValidationServiceProvider` - a service provider for Laravel 5 that registers the custom `Validation` class.
* Traits
	* `Traits\ChecksJoins` - allows a query to check if a table has already been joined.
	* `Traits\ChecksPaginationPage` - checks the pagination result set, and redirects to page 1 if there are no results. 
	* `Traits\CorrectsPaginatorPath` - corrects the path of the Laravel 5 `Paginator`.
	* `Traits\CorrectsTimezone` - allows easy conversion between timezones.
	* `Traits\CreatesSlugs` - provides an easy way of creating slugs from a `Request` object.
	* `Traits\DeletesDirectory` - provides a method for recursively deleting a directory.
	* `Traits\DistinctPaginte` - fixes the Laravel 5 `paginate()` method to work with distinct selects.
	* `Traits\Validatable` - creates an easy way of defining validation rules and messages that can be used in multiple locations.
* Validation
	* `Validation\LaravelValidator` - a Laravel 5 Validator that registers some custom validation rules.
	
### Javascript
* `CookieAcceptance` - a plugin for accepting the cookie policy, as per the EU cookie law.
* `DisableButtons` - a plugin that disables buttons when clicked.
* `EditableFields` - a plugin that allows any HTML element to be used for various AJAX requests.
* `OtherInputs` - a plugin to easily interact with an input which is used as an 'Other' entry in a dropdown.
* `SimpleMDE` - a plugin that configures the SimpleMDE editor.
* `ToggleVisibility` - a plugin to toggle the visibility of any element based on the value of a form input. 