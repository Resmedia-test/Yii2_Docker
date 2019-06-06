<?php
/**
 * Created by PhpStorm.
 * User: artemshmanovsky
 * Date: 02.12.16
 * Time: 23:43
 */

namespace frontend\components;


class OutLink
{
    protected $_protocols = array('ftp', 'http', 'https');
    protected $_prefix = '/go?url=';

    /**
     * @return OutLink
     */
    public static function load()
    {
        $linker = new OutLink();
        return $linker;
    }

    /**
     * Adds array of your protocols into protocols list
     * @param mixed $protocols like array('dc');
     * @return OutLink
     */
    public function addProtocols($protocols)
    {
        $this->_protocols = array_unique(array_merge($this->_protocols, $protocols));
        return $this;
    }

    /**
     * Replaces protocol list
     * @param mixed $protocols like array('http', 'https');
     * @return OutLink
     */
    public function setProtocols($protocols)
    {
        $this->_protocols = $protocols;
        return $this;
    }

    /**
     * Sets prefix for new URL generating
     * @param string $value
     * @return OutLink
     */
    public function setPrefix($value)
    {
        $this->_prefix = $value;
        return $this;
    }

    /**
     * @param string $html source HTML content
     * @return string processed content
     */
    public function process($html)
    {
        $protocols = implode('|', $this->_protocols);
        return preg_replace_callback('@\<a(?P<before>[^>]+)href=(?P<beginquote>[\'"]?)(?P<protocol>'. $protocols . ')://(?P<url>[^\'"\s]+)(?P<endquote>[\'"]?)(?P<after>[^>]+)?\>@i', array(get_class($this),'pregCallback') , $html);
    }

    protected function pregCallback($matches)
    {
        $before     = isset($matches['before'])     ? $matches['before']     : '';
        $beginquote = isset($matches['beginquote']) ? $matches['beginquote'] : '';
        $protocol   = isset($matches['protocol'])   ? $matches['protocol']   : '';
        $url        = isset($matches['url'])        ? $matches['url']        : '';
        $endquote   = isset($matches['endquote'])   ? $matches['endquote']   : '';
        $after      = isset($matches['after'])      ? $matches['after']      : '';

        if(substr($url,0,12) == 'testSite.zu' /*|| substr($url,0,20) == 'archive.site.ru'*/)
            return '<a ' . $before . ' href=' . $beginquote . $protocol . '://' . $url . $endquote . $after . '>';
        else
            return '<a rel="nofollow" class="modalForm"' . $before . 'href=' . $beginquote . $this->_prefix . $protocol . '://' . urlencode($url) . $endquote . $after . '>';
    }
}