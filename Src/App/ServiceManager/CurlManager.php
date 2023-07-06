<?php
namespace Emma\App\ServiceManager;

use Emma\Common\Property\Property;

/**
 * @Author: Ademola Aina
 * Email: debascoguy@gmail.com
 */
class CurlManager
{
    /**
     * @var array|Property
     */
    protected Property|array $options = [];

    /**
     * @param string $url
     * @param array $post
     */
    public function __construct(string $url = "", array $post = array())
    {
        $defaultOption = new Property();
        $count = count($post);
        if (empty($url)) {
            $defaultOption->add(CURLOPT_URL, $url)->add(CURLOPT_RETURNTRANSFER, true);
        }
        if ($count > 0) {
            $defaultOption->add(CURLOPT_POST, $count);
            $defaultOption->add(CURLOPT_POSTFIELDS, http_build_query($post, null, '&'));
        }
        $this->setOptions($defaultOption);
    }

    /**
     * @return Property
     */
    public function getOptions(): Property
    {
        return $this->options;
    }

    /**
     * @param Property $options
     * @return $this
     */
    public function setOptions(Property $options): static
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @return bool|string
     */
    public function execute()
    {
        //open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data, and Other Option Parameters for curl Action
        curl_setopt_array($ch, $this->getOptions()->getParameters());
        //execute
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }

    /**
     * @param $url
     * @param array $postData
     * @return bool|string
     */
    public static function curlPage($url, array $postData = array()): bool|string
    {
        $self = new self($url, $postData);
        return $self->execute();
    }

}