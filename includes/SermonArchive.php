<?php

namespace Lightpost;

class SermonArchive
{
	protected $api_key;
	protected $page_id;
	protected $sermons;
	protected $sermon;
	protected $sermon_id;
	protected $error = false;
	protected $error_message;
	
	public function __construct()
	{
		$this->api_key = get_option('lightpost_api_key');
        $this->page_id = (int) get_option('lightpost_sermon_archive_page_id');
        
        add_filter('the_content', [$this, 'getContent'], 100);
    }
    
    public function getContent($content)
    {
        global $post;

		if ($this->page_id !== $post->ID) {
			return $content;
        }
        
        $this->loadData();
        
        if ($this->error) {
            return 'Unable to load sermon archive data.';
        }
        
        if (is_array($this->sermons)) {
            ob_start();
            echo $content;
            include dirname(__DIR__).'/views/sermons.php';
            return ob_get_clean();
        }

        if (is_array($this->sermon)) {
            ob_start();
            echo $content;
            include dirname(__DIR__).'/views/sermon.php';
            return ob_get_clean();
        }
        
        return $content;
	}
	
	public function loadData()
	{
		$this->sermon_id = sanitize_text_field($_GET['sermon_id']);
		
		if (!empty($this->sermon_id)) {
			$this->loadSermonData($this->sermon_id);
		}
		else {
			$this->loadSermonsData();
        }
	}
	
	public function loadSermonData($sermon_id)
	{
		if ($this->sermon) {
			return;
		}
		
		$response = wp_remote_get(LIGHTPOST_API_DOMAIN.'/sermons/'.$sermon_id, [
		            'headers' => [
		                'Authorization' => $this->api_key,
		            ],
		        ]);
		
		if (!is_array($response)) {
			$this->error = true;
			
			return;
		}
		
		if ($response['response']['code'] === 404) {
			$this->error = true;
			
			return;
		}
		
		$this->sermon = json_decode($response['body'], true);
	}
	
	public function loadSermonsData()
	{
		if ($this->sermons) {
			return;
		}

		$filters = [
			'query'      => sanitize_text_field($_GET['query']),
			'type'       => sanitize_text_field($_GET['type']),
			'_page'      => sanitize_text_field($_GET['_page']),
			'api_token'  => $this->api_key,
		];

		$response = wp_remote_get(LIGHTPOST_API_DOMAIN.'/sermons?' . http_build_query($filters), [
            'headers' => [
                'Authorization' => $this->api_key,
            ],
		]);
		
        if (!is_array($response)) {
            $this->error = true;
            return;
        }
        if ($response['response']['code'] === 404) {
            $this->error = true;
            return;
        }
		
		$response = json_decode($response['body'], true);
		
		$this->sermons = $response;
    }
}
