<?php

namespace Lightpost;

class Directory
{
	protected $api_key;
	protected $page_id;
	protected $directory;
	protected $family;
	
	public function __construct()
	{
		$this->api_key = get_option('lightpost_api_key');
		$this->page_id = (int) get_option('lightpost_directory_page_id');
		
		add_filter('the_content', [$this, 'getContent']);
	}
	
	public function loadData()
	{
		global $post;
		
		if ($this->page_id !== $post->ID || get_option('lightpost_directory_disclaimer') != 'true') {
			return;
		}
		
		if(isset($_GET['family'])) {
			$this->loadFamilyData();
		} else {
			$this->loadDirectoryData();
		}
	}
	
    public function getContent($content)
    {
		$this->loadData();

        if ($this->error) {
            return 'Unable to load directory information!  Please try again later.';
        }
		
		if (is_array($this->directory)) {
            ob_start();
            include dirname(__DIR__).'/views/directory.php';
            return ob_get_clean();
        }
		
		if (is_array($this->family)) {
            ob_start();
            include dirname(__DIR__).'/views/family.php';
            return ob_get_clean();
        }
		
		return $content;
	}
	
	public function loadDirectoryData()
	{
		if ($this->directory) {
			return;
		}

		$filters = [
			'member_name' => sanitize_text_field($_GET['member_name']),
			'_page' => sanitize_text_field($_GET['_page']),
			'api_token' => $this->api_key,
		];

		$response = wp_remote_get(LIGHTPOST_API_DOMAIN.'/directory?' . http_build_query($filters), [
            'headers' => [
                'Authorization' => $this->api_key,
			]
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
		
		$this->directory = $response;
	}
	
	public function loadFamilyData()
	{
		if ($this->family) {
			return;
		}

		$filters = [
			'family_id' => sanitize_text_field($_GET['family']),
			'api_token' => $this->api_key,
		];

		$response = wp_remote_get(LIGHTPOST_API_DOMAIN.'/directory/family/' . sanitize_text_field($_GET['family']) . '?' . http_build_query($filters), [
            'headers' => [
                'Authorization' => $this->api_key,
			]
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
		
		$this->family = $response;
	}
}
