<?php
namespace Swark\TemplateOperators;

/**
 * Modifies URLs by replacing parts or adding to the path or query parameters
 * It is inspired by Django Spurl: https://github.com/j4mie/django-spurl
 *
 * If no url is specified it uses the current url (if available0.
 * Use false as url to create one with no data.
 *
 * Supported parameters:
 * scheme - Set scheme
 * host - Set host
 * port - Set port
 * auth - Set username and password, must be array with two entries.
 * path - Set path
 * add_path - Add to the current path, can be an array with path entries
 * fragment - Set fragment
 * query - Set the entire query line as a string, or encoded from an associative array
 * add_query - Adds values to query entries, or sets them if missing.
 * set_query - Sets/override query variables
 * remove_query - Remove query variables, name of variable or array with names
 */
class UrlModifier extends \SwarkOperator
{
    function __construct()
    {
        parent::__construct('url_modify', 'params');
    }

    static function execute($value, $namedParameters)
    {
        if (!$value && $value !== false) {
            if (PHP_SAPI != 'cli') {
                $https = $_SERVER['HTTPS'];
                $scheme = ($https == 'off' || $https == '') ? 'http' : 'https';
                $url = $scheme . '://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
                if ($_SERVER['QUERY_STRING']) {
                    $url .= '?' . $_SERVER['QUERY_STRING'];
                }
            } else {
                $url = '';
            }
        } else {
            $url = $value;
        }
        $params = $namedParameters['params'];

        return self::modifyUrl($url, $params);
    }

    static function modifyUrl($url, $params)
    {
        $parts = parse_url($url);
        if (isset($params['scheme'])) {
            $parts['scheme'] = $params['scheme'];
        }
        if (isset($params['host'])) {
            $parts['host'] = $params['host'];
        }
        if (isset($params['port'])) {
            $parts['port'] = $params['port'];
        }
        if (isset($params['auth'])) {
            $parts['user'] = $params['auth'][0];
            $parts['pass'] = $params['auth'][1];
            if (!isset($parts['host'])) {
                $parts['host'] = $_SERVER['SERVER_NAME'];
                if (!isset($parts['port'])) {
                    $scheme = isset($parts['scheme']) ? $parts['scheme'] : null;
                    $port = $_SERVER['SERVER_PORT'];
                    if (!$scheme || ($scheme == 'http' && $port != 80) || ($scheme == 'https' && $port != 443)) {
                        $parts['port'] = $port;
                    }
                }
            }
        }
        if (isset($params['path'])) {
            $parts['path'] = $params['path'];
        }
        if (isset($params['add_path'])) {
            $extraPath = $params['add_path'];
            if (is_array($extraPath)) {
                $extraPath = implode('/', $extraPath);
            }
            if (isset($parts['path'])) {
                if (substr($parts['path'], -1, 1) != '/') {
                    $parts['path'] .= '/';
                }
                $parts['path'] .= $extraPath;
            } else {
                $parts['path'] = '/' . $extraPath;
            }
        }
        if (isset($params['fragment'])) {
            $parts['fragment'] = $params['fragment'];
        }
        if (isset($params['query'])) {
            if (is_array($params['query'])) {
                $parts['query_vars'] = $params['query'];
            } else {
                $parts['query'] = $params['query'];
            }
        }
        if (isset($params['add_query'])) {
            if (isset($parts['query'])) {
                parse_str($parts['query'], $queryVars);
                $parts['query_vars'] = $queryVars;
            } else if (!isset($parts['query_vars'])) {
                $parts['query_vars'] = array();
            }
            foreach ($params['add_query'] as $name => $value) {
                if (isset($parts['query_vars'][$name])) {
                    if (!is_array($parts['query_vars'][$name])) {
                        $parts['query_vars'][$name] = array($parts['query_vars'][$name]);
                    }
                    if (!is_array($value)) {
                        $value = array($value);
                    }
                    $parts['query_vars'][$name] = array_merge($parts['query_vars'][$name], $value);
                } else {
                    $parts['query_vars'][$name] = $value;
                }
            }
        }
        if (isset($params['set_query'])) {
            if (isset($parts['query'])) {
                parse_str($parts['query'], $queryVars);
                $parts['query_vars'] = $queryVars;
            } else if (!isset($parts['query_vars'])) {
                $parts['query_vars'] = array();
            }
            $parts['query_vars'] = array_merge($parts['query_vars'], $params['set_query']);
        }
        if (isset($params['remove_query'])) {
            if (isset($parts['query'])) {
                parse_str($parts['query'], $queryVars);
                $parts['query_vars'] = $queryVars;
            } else if (!isset($parts['query_vars'])) {
                $parts['query_vars'] = array();
            }
            if (is_array($params['remove_query'])) {
                foreach ($params['remove_query'] as $name) {
                    unset($parts['query_vars'][$name]);
                }
            } else {
                unset($parts['query_vars'][$params['remove_query']]);
            }
        }

        $url = self::buildUrl($parts);

        return $url;
    }

    static function buildUrl($parts)
    {
        $url = '';
        if (isset($parts['scheme'])) {
            $url .= $parts['scheme'] . '://';
        }
        if (isset($parts['host'])) {
            if (!isset($parts['scheme'])) {
                $url .= '//';
            }
            if (isset($parts['pass']) || isset($parts['user'])) {
                if (isset($parts['user']))
                    $url .= $parts['user'];
                if (isset($parts['pass']))
                    $url .= ':' . $parts['pass'];
                $url .= '@';
            }
            $url .= $parts['host'];
            if (isset($parts['port'])) {
                $url .= ':' . $parts['port'];
            }
        }
        if (isset($parts['path'])) {
            $url .= $parts['path'];
        }
        if (isset($parts['query_vars'])) {
            $parts['query'] = http_build_query($parts['query_vars']);
            if (!$parts['query']) {
                unset($parts['query']);
            }
        }
        if (isset($parts['query'])) {
            $url .= '?' . $parts['query'];
        }
        if (isset($parts['fragment'])) {
            $url .= '#' . $parts['fragment'];
        }
        return $url;
    }
}