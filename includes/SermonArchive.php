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
		
		global $page;

		$filters = [
			'query' => sanitize_text_field($_GET['query']),
			'type'  => sanitize_text_field($_GET['type']),
		];

		$response = wp_remote_get(LIGHTPOST_API_DOMAIN.'/sermons?' . http_build_query(array_merge(['api_token' => $this->api_key], ['page' => $page], $filters)), [
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
	
	public function getLinks()
	{
		// Get the current Wordpress URL without the page number
		$obj_id = get_queried_object_id();
		$current_wp_url = get_permalink( $obj_id );

		// Search options
		$filters = [
			'query' => sanitize_text_field($_GET['query']),
			'type'  => sanitize_text_field($_GET['type']),
		];

		// Next/Previous Links
		$previous_pagination_url = esc_url($current_wp_url . '?' . http_build_query(array_merge($filters, ['page' => $this->pagination['current_page'] - 1])));
		$next_pagination_url = esc_url($current_wp_url . '?' . http_build_query(array_merge($filters, ['page' => $this->pagination['current_page'] + 1])));

		if($this->pagination['per_page'] <= $this->pagination['total']):
			?>
			<ul class="pagination" role="navigation">
				<?php // Previous Page Link ?>
				<?php if ($this->pagination['current_page'] == 1): ?>
					<li class="disabled btn btn-secondary mr-1" aria-disabled="true" aria-label="@lang('pagination.previous')">
						<span aria-hidden="true">&lsaquo;</span>
					</li>
				<?php else: ?>
					<li class="mr-1">
						<a href="<?php echo $previous_pagination_url; ?>" class="btn btn-secondary mr-1" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo; Previous</a>
					</li>
				<?php endif; ?>

				<?php // Pagination Elements ?>
				

				<?php // Next Page Link ?>
				<?php if ($this->pagination['current_page'] < $this->pagination['last_page']): ?>
					<li>
						<a href="<?php echo $next_pagination_url; ?>" class="btn btn-secondary" rel="next" aria-label="@lang('pagination.next')">Next &rsaquo;</a>
					</li>
				<?php else: ?>
					<li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
						<span aria-hidden="true">&rsaquo;</span>
					</li>
				<?php endif; ?>

				<li class="align-middle ml-3">Page <?php echo esc_html($this->pagination['current_page']); ?> of <?php echo esc_html($this->pagination['last_page']); ?></li>
			</ul>
			<?php
		endif;
	}
}
