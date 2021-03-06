<?php
/**
* URL Plugin
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*
* @author David Carr - dave@daveismyname.com
*/

namespace Libs;
use Libs\Session;
use Libs\Inflector;
/**
 * Collection of methods for working with urls.
 */
class Url
{
    /**
     * Redirect to chosen url.
     *
     * @param string $url      the url to redirect to
     * @param bool   $fullpath if true use only url in redirect instead of using DIR
     * @param int $code the server status code for the redirection
     */
    public static function redirect($url = null, $fullpath = false, $code = 200)
    {
        $url = ($fullpath === false) ? DIR.$url : $url;
        if ($code == 200) {
            header('Location: '.$url);
        } else {
            header('Location: '.$url, true, $code);
        }
        exit;
    }
    /**
     * Detect the true URI
     *
     * * @return string parsed URI
     */
    public static function detectUri()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $pathName = dirname($scriptName);
        if (strpos($requestUri, $scriptName) === 0) {
            $requestUri = substr($requestUri, strlen($scriptName));
        } else if (strpos($requestUri, $pathName) === 0) {
            $requestUri = substr($requestUri, strlen($pathName));
        }
        $uri = parse_url(ltrim($requestUri, '/'), PHP_URL_PATH);
        if (! empty($uri)) {
            return str_replace(array('//', '../'), '/', $uri);
        }
        // Empty URI of homepage; internally encoded as '/'
        return '/';
    }
    /**
     * Created the absolute address to the user profile image.
     *  <?=SITE_URL?>assets/images/profile-pics/username/image
     * @param $username
     * @param $image
     * @return string url to profile image
     */
    public static function profileImageURL($image = null)
    {
        return SITE_URL .'assata/images/profile-pics/'.$image;
    }
    /**
     * Created the absolute address to the template folder.
     *  <?=SITE_URL?>Templates/Default/Assets/
     * @param  boolean $custom
     * @return string url to template folder
     */
    public static function templatePath($custom = DEFAULT_TEMPLATE, $folder = '/Assets/')
    {
        return SITE_URL .'Templates/' .$custom .$folder;
    }
    /**
     * Created the relative address to the template folder.
     *
     * @param  boolean $custom
     * @return string path to template folder
     */
    public static function relativeTemplatePath($custom = DEFAULT_TEMPLATE, $folder = '/Assets/')
    {
        return 'Templates/' .$custom .$folder;
    }
    /**
     * Converts plain text urls into HTML links, second argument will be
     * used as the url label <a href=''>$custom</a>.
     *
     *
     * @param  string $text   data containing the text to read
     * @param  string $custom if provided, this is used for the link label
     *
     * @return string         returns the data with links created around urls
     */
    public static function autoLink($text, $custom = null)
    {
        $regex   = '@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@';
        if ($custom === null) {
            $replace = '<a href="http$2://$4">$1$2$3$4</a>';
        } else {
            $replace = '<a href="http$2://$4">'.$custom.'</a>';
        }
        return preg_replace($regex, $replace, $text);
    }
    /**
     * This function converts and url segment to an safe one, for example:
     * `test name @132` will be converted to `test-name--123`
     * Basicly it works by replacing every character that isn't an letter or an number to an dash sign
     * It will also return all letters in lowercase.
     *
     * @param $slug - The url slug to convert
     *
     * @return mixed|string
     */
    public static function generateSafeSlug($slug)
    {
        setlocale(LC_ALL, "en_US.utf8");
        $slug = preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $slug));
        $slug = htmlentities($slug, ENT_QUOTES, 'UTF-8');
        $pattern = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $slug = preg_replace($pattern, '$1', $slug);
        $slug = html_entity_decode($slug, ENT_QUOTES, 'UTF-8');
        $pattern = '~[^0-9a-z]+~i';
        $slug = preg_replace($pattern, '-', $slug);
        return strtolower(trim($slug, '-'));
    }
    /**
     * Go to the previous url.
     */
    public static function previous()
    {
        header('Location: '. $_SERVER['HTTP_REFERER']);
        exit;
    }
    /**
     * Get all url parts based on a / seperator.
     *
     * @return array of segments
     */
    public static function segments()
    {
        return explode('/', $_SERVER['REQUEST_URI']);
    }
    /**
     * Get item in array.
     *
     * @param  array $segments array
     * @param  int $id array index
     *
     * @return string - returns array index
     */
    public static function getSegment($segments, $id)
    {
        if (array_key_exists($id, $segments)) {
            return $segments[$id];
        }
    }
    /**
     * Get last item in array.
     *
     * @param  array $segments
     * @return string - last array segment
     */
    public static function lastSegment($segments)
    {
        return end($segments);
    }
    /**
     * Get first item in array
     *
     * @param  array segments
     * @return int - returns first first array index
     */
    public static function firstSegment($segments)
    {
        return $segments[0];
    }

    static function part($number){
        $parts = explode("/", $_SERVER["REQUEST_URI"]);
        return (isset($parts[$number])) ? $parts[$number] : false;
    }
}
