<?php

namespace Lightpost;

class SermonArchive
{
	protected $api_key;
	protected $page_id;
	protected $sermons;
	protected $authors;
	protected $sermon;
	protected $sermon_id;
	
	public function __construct()
	{
		$this->api_key = get_option('lightpost_api_key');
		$this->page_id = (int) get_option('lightpost_sermon_archive_page_id');
		
		add_filter('document_title_parts', [$this, 'getPageTitle']);
		add_filter('the_content', [$this, 'getContent']);

		$this->get_sermon_id = sanitize_text_field($_GET['sermon_id']);
	}
	
	public function loadData()
	{
		global $post;
		
		if ($this->page_id !== $post->ID) {
			return;
		}
		
		if (!empty($this->get_sermon_id)) {
			$this->loadSermonData($this->get_sermon_id);
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
		
		$this->sermons = $response['data'];
		$this->pagination = [
			'current_page' => $response['current_page'],
			'first_page_url' => $response['first_page_url'],
			'from' => $response['from'],
			'last_page' => $response['last_page'],
			'last_page_url' => $response['last_page_url'],
			'next_page_url' => $response['next_page_url'],
			'path' => $response['path'],
			'per_page' => $response['per_page'],
			'prev_page_url' => $response['prev_page_url'],
			'to' => $response['to'],
			'total' => $response['total'],
		];
    }
    public function getPageTitle($title)
    {
        $this->loadData();
        if ($this->sermon) {
            $title['title'] = $this->sermon['title'];
        }
        return $title;
    }
    public function getContent($content)
    {
        $this->loadData();
        if ($this->error) {
            return 'Unable to load sermon archive data.';
        }
        if (is_array($this->sermons)) {
            ob_start();
            include dirname(__DIR__).'/views/sermons.php';
            return ob_get_clean();
        }
        if (is_array($this->sermon)) {
            ob_start();
            include dirname(__DIR__).'/views/sermon.php';
            return ob_get_clean();
        }
        return $content;
	}
}
