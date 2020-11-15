<?php
/**
 * Class PHP_Warmer
 */
class PHP_Warmer
{
    var $config;
    var $response;
    var $timer;
    var $sleep_time;
    var $context;
    var $from;
    var $to;
    var $urlProblems = array();

    function __construct($config)
    {
        $this->config = array_merge(
           array(
               // 'key' => 'default',
               'reportProblematicUrls' => false
           ), $config
        );
        $this->sleep_time = (int)$this->get_parameter('sleep', 0);
        $this->from = (int)$this->get_parameter('from', 0);
        $this->to = (int)$this->get_parameter('to', false);
        $this->response = new PHP_Warmer_Response();
        $this->context = stream_context_create(
            /****
			UNCOMMENT THIS IF YOU USE AN HTTP LOGIN, COMMONLY USED ON TEST ENVS
			array (
				'http' => array (
					'header' => 'Authorization: Basic ' . base64_encode("youruser:yourpassword")
				)
			)
			*/
        );
    }

    function run()
    {
        // Disable time limit
        set_time_limit(0);
        $counter = 0;
        // Authenticate request
        if($this->authenticated_request())
        {
            // URL properly added in GET parameter
            if(($sitemap_url = $this->get_parameter('url')) !== '')
            {
                //Start timer
                $timer = new PHP_Warmer_Timer();
                $timer->start();

                // Discover URL links
                $urls = $this->process_sitemap($sitemap_url);
                sort($urls);

                // Visit links
                foreach($urls as $url)
                {
                    if($this->from <= $counter &&
                            (empty($this->to) || (!empty($this->to) && $this->to > $counter) )) {
                        $url_content = @file_get_contents(trim($url),false,$this->context);

                        // Prepare info about URLs with error
                        if ($url_content === false && $this->config['reportProblematicUrls']) {
                            $this->urlProblems[] = $url;
                        }

                        if(($this->sleep_time > 0))
                            sleep($this->sleep_time);

                        $this->response->set_visited_url($url);
                    }
                    $counter++;
                }

                //Stop timer
                $timer->stop();

                // Send timer data to response
                $this->response->set_duration($timer->duration());

                // Done!
                if(sizeof($urls) > 0)
                    $this->response->set_message("Processed sitemap: {$sitemap_url}");
                else
                    $this->response->set_message("Processed sitemap: {$sitemap_url} - but no URL:s were found", 'ERROR');
            }
            else
            {
                $this->response->set_message('Empty url parameter', 'ERROR');
            }
        }
        else
        {
            $this->response->set_message('Incorrect key', 'ERROR');
        }

        if ($this->config['reportProblematicUrls'] && count($this->urlProblems) > 0) {
            @mail($this->config['reportProblematicUrlsTo'], 'Warming cache ends with errors', "Those URLs cannot be warmed:\n" . implode("\n", $this->urlProblems));
        }

        $this->response->display();
    }

    function process_sitemap($url)
    {
        // URL:s array
        $urls = array();

        // Grab sitemap and load into SimpleXML
        $sitemap_xml = @file_get_contents($url,false,$this->context);
	$sitemap_xml = preg_replace("/<\?xml (.+?) \?>/","",$sitemap_xml);

        if(($sitemap = @simplexml_load_string($sitemap_xml)) !== false)
        {
            // Process all sub-sitemaps
            if(count($sitemap->sitemap) > 0)
            {
                foreach($sitemap->sitemap as $sub_sitemap)
                {
                    $sub_sitemap_url = (string)$sub_sitemap->loc;
                    $urls = array_merge($urls, $this->process_sitemap($sub_sitemap_url));
                    $this->response->log("Processed sub-sitemap: {$sub_sitemap_url}");
                }
            }

            // Process all URL:s
            if(count($sitemap->url) > 0)
            {
                foreach($sitemap->url as $single_url)
                {
                    $urls[] = (string)$single_url->loc;
                }
            }

            return $urls;
        }
        else
        {
            $this->response->set_message('Error when loading sitemap.', 'ERROR');
            return array();
        }
    }

    /**
     * @return bool
     */
    function authenticated_request()
    {
        return ($this->get_parameter('key') === $this->config['key']) ? true : false;
    }

    /**
     * @param $key
     * @param string $default_value
     * @return mixed
     */
    function get_parameter($key,  $default_value = '')
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default_value;
    }
}
