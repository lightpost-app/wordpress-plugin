<link href="<?php echo plugins_url('css/lightpost.css', dirname(__FILE__)) ?>" rel="stylesheet">

<div class="lp-bootstrap">

<div class="container p-0">
<div class="row">
    <div class="col-md-7">
        <form class="form-inline">
            <p>
                <input type="text" name="query" class="form-control" style="color: black" placeholder="Search..." value="<?php echo sanitize_text_field($_GET['query']); ?>">
                <select class="form-control" name="type" style="color: black">
                    <option value="speaker" <?php echo sanitize_text_field($_GET['type']) == 'speaker' ? 'selected' : ''; ?>>Speaker</option>
                    <option value="title" <?php echo sanitize_text_field($_GET['type']) == 'title' ? 'selected' : ''; ?>>Title</option>
                </select>
                <button class="form-control btn btn-primary" type="submit">Search</button>
            </p>
        </form>
    </div>
    <div class="col-md-5 text-right">
        <div class="btn-group" role="group" aria-label="Basic example">
            <a class="text-decoration-none btn btn-primary text-light" href="?_page=<?php echo ($this->pagination['current_page'] - 1 < 1 ? '1' : $this->pagination['current_page'] - 1); ?><?php echo sanitize_text_field($_GET['query']) ? '&query=' . sanitize_text_field($_GET['query']) : null; ?><?php echo sanitize_text_field($_GET['type']) ? '&type=' . sanitize_text_field($_GET['type']) : null; ?>">Prev</a>
            <a class="text-decoration-none btn btn-outline-primary">Page <?php echo $this->pagination['current_page']; ?> of <?php echo $this->pagination['last_page']; ?></a>
            <a class="text-decoration-none btn btn-primary text-light" href="?_page=<?php echo ($this->pagination['current_page'] + 1 <= $this->pagination['last_page'] ? $this->pagination['current_page'] + 1 : $this->sermons['last_page']); ?><?php echo sanitize_text_field($_GET['query']) ? '&query=' . sanitize_text_field($_GET['query']) : null; ?><?php echo sanitize_text_field($_GET['type']) ? '&type=' . sanitize_text_field($_GET['type']) : null; ?>">Next</a>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table table-bordered table-condensed table-striped" width="100%">
            <thead class="thead-light">
                <tr>
                    <th align="left" width="15%">Date</th>
                    <th align="left" width="50%">Title</th>
                    <th align="left" width="30%">Speaker</th>
                    <th align="left" width="5%">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($this->sermons): ?>
                    <?php foreach ($this->sermons as $sermon): ?>
                        <tr>
                            <td>
                                <?php echo esc_html(Lightpost\Util::date($sermon['date_sermon'], 'M j, Y')) ?>
                            </td>
                            <td>
                                <strong><?php echo esc_html($sermon['title']) ?></strong>
                            </td>
                            <td>
                                <?php if ($sermon['speaker']): ?>
                                    <?php echo esc_html($sermon['speaker']) ?>
                                <?php endif ?>
                            </td>
                            <td>
                                <?php if(is_array($sermon['files']) && is_array($sermon['files'][0]) && !empty($sermon['files'][0]['link'])): ?>
                                    <a href="<?php echo esc_url($sermon['files'][0]['link']) ?>" class="btn btn-sm btn-secondary">
                                        Play
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php else: ?>
                    <tr>
                        <td class="p-5" colspan="5">No sermons found.</td>
                    </tr>
                <?php endif ?>
            </tbody>
        </table>

    </div>
</div>
</div>

</div>
