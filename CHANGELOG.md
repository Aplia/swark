# Changelog for Swark

## 1.2.1

* Added operator 'url_modify', which makes it easier to modify the components
  of a url.

## 1.2.0

* Defined all operators in swark.ini, making even the ones supplied by swark overridable.
* Added support for disabling operators by setting the class to `disabled`.

## 1.1.0

* Fixed `json_encode` operator to support values as a parameter or as the pipe input.
  This makes it compatible with the version in `jscore`.
* Added support for easily adding custom operators by defining them in `swark.ini`.

## 1.0.0

Initial version with composer support.
