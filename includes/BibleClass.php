<?php

namespace Lightpost;

class BibleClass
{
	protected $api_key;
	protected $page_id;
	protected $bible_classes;
	protected $authors;
	protected $sermon;
	
	public function __construct()
	{
		$this->api_key = get_option('lightpost_api_key');
		$this->page_id = (int) get_option('lightpost_bible_class_registration_page_id');
		
		add_filter('the_content', [$this, 'getContent']);
		// add_shortcode('custom_form', array($this, 'shortcode_handler'));
	}
	
	public function loadData()
	    {
		global $post;
		
		if ($this->page_id !== $post->ID) {
			return;
		}
		
		$this->loadBibleClassData();
	}
	
	public function loadBibleClassData()
	{
		if ($this->bible_classes) {
			return;
		}

		$response = wp_remote_get(LIGHTPOST_API_DOMAIN.'/bible-class-groups?api_token=' . $this->api_key, [
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
		
		$this->bible_classes = $response;
	}
	
	public function submitRegistration()
	{
		global $page;

		if (!empty(sanitize_text_field($_POST['nonce_custom_form'])))
		{
			if (!wp_verify_nonce(sanitize_text_field(sanitize_text_field($_POST['nonce_custom_form'])), 'handle_custom_form'))
			{
				die('You are not authorized to perform this action.');
			} if (sanitize_text_field($_POST['url']) > '') {
				$this->success_message = false;
				$this->error = true;
			} else
			{
				$error = null;
				$response = wp_remote_post(LIGHTPOST_API_DOMAIN.'/bible-classes/register?api_token=' . $this->api_key, [
					'headers' => [
						'Authorization' => $this->api_key,
					],
					'body' => [
						'name'    => sanitize_text_field(sanitize_text_field($_POST['lightpost_name'])), 
						'email'   => sanitize_text_field(sanitize_text_field($_POST['lightpost_email'])),
						'classes' => $this->recursive_sanitize_text_field($_POST['selected_classes']),
					],
				]);

				if (!is_array($response)) {
					print_r($response);
					exit;
					$this->error = true;
					return;
				}
				if ($response['response']['code'] === 404) {
					$this->error = true;
					return;
				}
				if ($response['response']['code'] === 400) {
					$this->error = true;
					return;
				}
				
				$this->success_message = true;
			}
		}
	}
	
    public function getContent($content)
    {
		$this->loadData();
		
		if(sanitize_text_field($_POST['sub_19493']) == 'sub_11492') {
			$this->submitRegistration();
		}

        if ($this->error) {
            return 'Unable to load Bible class data!  Please try again later.';
        }
		
		if (is_array($this->bible_classes)) {
            ob_start();
            include dirname(__DIR__).'/views/bible_classes.php';
            return ob_get_clean();
        }
		
		if (is_array($this->sermon)) {
            ob_start();
            include dirname(__DIR__).'/views/bible_class.php';
            return ob_get_clean();
        }
		
		return $content;
	}

	private function recursive_sanitize_text_field($array) 
	{
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = $this->recursive_sanitize_text_field($value);
			}
			else {
				$value = sanitize_text_field( $value );
			}
		}

		return $array;
	}
}
