<link href="<?php echo plugins_url('css/lightpost.css', dirname(__FILE__)) ?>" rel="stylesheet">

<div class="lp-bootstrap">
    <div class="container p-0">
        <div class="row">
            <div class="col-sm-6">
                <form class="form form-inline" id="submit-form">
                    <input type="text" placeholder="Search by name..." name="member_name" id="member_name" class="form-control d-block" style="color: black" value="<?php echo sanitize_text_field($_GET['member_name']); ?>" autocomplete="off"/>
                    <button type="submit" class="btn btn-primary d-none d-sm-inline ml-2">Search</button>
                </form>
            </div>
            <div class="col-sm-6 text-right">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a class="text-decoration-none btn btn-primary text-light" href="?_page=<?php echo ($this->directory['current_page'] - 1 < 1 ? '1' : $this->directory['current_page'] - 1); ?><?php echo sanitize_text_field($_GET['member_name']) ? '&member_name=' . sanitize_text_field($_GET['member_name']) : null; ?>">Prev</a>
                    <a class="text-decoration-none btn btn-outline-primary">Page <?php echo $this->directory['current_page']; ?> of <?php echo $this->directory['last_page']; ?></a>
                    <a class="text-decoration-none btn btn-primary text-light" href="?_page=<?php echo ($this->directory['current_page'] + 1 <= $this->directory['last_page'] ? $this->directory['current_page'] + 1 : $this->directory['last_page']); ?><?php echo sanitize_text_field($_GET['member_name']) ? '&member_name=' . sanitize_text_field($_GET['member_name']) : null; ?>">Next</a>
                </div>
            </div>
        </div>
        <br>
        <div class="row px-0">
            <?php if ($this->directory): ?>
                <?php foreach($this->directory['data'] as $user): ?>
                    <div class="col-lg-6">
                        <a href="?family=<?php echo $user['family_id']; ?>" style="text-decoration: none;">
                            <div class="card mb-2 py-1 shadow-sm" style="border-color: #555;">
                                <div class="card-body py-0 px-2">
                                    <strong><?php echo $user['last_name']; ?></strong>, <?php echo $user['first_name']; ?>
                                    <small class="text-muted">family</small>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div style="text-align: center;">No directory information found.</div>
            <?php endif ?>
        </div>
    </div>
</div>
