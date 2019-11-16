<?php

namespace Lightpost;

class Directory
{
	protected $api_key;
	protected $page_id;
	protected $directory;
	protected $family;
	protected $error = false;
	protected $error_message;
	
	public function __construct()
	{
		$this->api_key = get_option('lightpost_api_key');
		$this->page_id = (int) get_option('lightpost_directory_page_id');
		
		add_filter('the_content', [$this, 'getContent'], 100);
	}
	
    public function getContent($content)
    {
		global $post;
		
		if ($this->page_id !== $post->ID) {
			return $content;
        }
        
        // If page is password protected and not authenticated, return existing content.
		if(post_password_required()) {
			return $content;
		}
		// If we have not agreed to the terms for this page, error out.
		if(get_option('lightpost_directory_disclaimer') != 'true') {
			return 'Cannot load content: the Lightpost member directory page disclaimer is not checked.';
        }
        
        $this->loadData();

        if ($this->error) {
            return $this->error_message ?: 'Unable to load directory information!  Please try again later.';
        }
		
		if (is_array($this->directory)) {
            ob_start();
            echo $content;
            include dirname(__DIR__).'/views/directory.php';
            return ob_get_clean();
        }
		
		if (is_array($this->family)) {
            ob_start();
            echo $content;
            include dirname(__DIR__).'/views/family.php';
            return ob_get_clean();
        }
		
		return $content;
	}
	
	public function loadData()
	{
		if(isset($_GET['family'])) {
			$this->loadFamilyData();
		} else {
			$this->loadDirectoryData();
		}
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
            $this->error_message = 'The API response was not properly formatted.';
            return;
        }
        if ($response['response']['code'] === 404) {
            $this->error = true;
            $this->error_message = 'The API response code was 404.';
            return;
        }
		
		$this->directory = json_decode($response['body'], true);
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
            $this->error_message = 'The API response was not properly formatted.';
            return;
        }
        if ($response['response']['code'] === 404) {
            $this->error = true;
            $this->error_message = 'The API response code was 404.';
            return;
        }
        
        $this->family = json_decode($response['body'], true);
	}
}
