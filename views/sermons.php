<div class="lightpost_sermon_archive">
    <form class="form-inline">
        <p>
            <input type="text" name="query" class="form-control" placeholder="Search here..." value="<?php echo sanitize_text_field($_GET['query']); ?>">
            <select class="form-control" name="type">
                <option value="speaker" <?php echo sanitize_text_field($_GET['type']) == 'speaker' ? 'selected' : ''; ?>>Speaker</option>
                <option value="title" <?php echo sanitize_text_field($_GET['type']) == 'title' ? 'selected' : ''; ?>>Title</option>
            </select>
            <button class="form-control btn btn-primary" type="submit">Search</button>
        </p>
    </form>
    <table class="lightpost_sermon_archive__table lightpost_table lightpost_table-condensed lightpost_table-striped" width="100%">
        <thead>
            <tr>
                <th class="lightpost_sermon_archive__table_date_header" align="left" width="15%">Date</th>
                <th class="lightpost_sermon_archive__table_title_header" align="left" width="50%">Title</th>
                <th class="lightpost_sermon_archive__table_author_header" align="left" width="30%">Speaker</th>
                <th class="lightpost_sermon_archive__table_passage_header" align="left" width="5%">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($this->sermons): ?>
                <?php foreach ($this->sermons as $sermon): ?>
                    <tr>
                        <td class="lightpost_sermon_archive__table_date">
                            <?php echo esc_html(Lightpost\Util::date($sermon['date_sermon'], 'M j, Y')) ?>
                        </td>
                        <td class="lightpost_sermon_archive__table_title">
                            <strong><?php echo esc_html($sermon['title']) ?></strong>
                        </td>
                        <td class="lightpost_sermon_archive__table_author">
                            <?php if ($sermon['speaker']): ?>
                                <?php echo esc_html($sermon['speaker']) ?>
                            <?php endif ?>
                        </td>
                        <td class="lightpost_sermon_archive__table_passage">
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
                    <td class="lightpost_sermon_archive__table_no_sermons_found" colspan="5">No sermons found.</td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>

    <?php echo $this->getLinks(); ?>

</div>
