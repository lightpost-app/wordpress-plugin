<link href="<?php echo plugins_url('css/lightpost.css', dirname(__FILE__)) ?>" rel="stylesheet">

<div class="lp-bootstrap">

<div class="container p-0">
<div class="row">
<div class="col-12">

    <?php if($this->success_message): ?>
        <div class="alert alert-success" role="alert">
            Registration submitted successfully!
        </div>
    <?php endif; ?>
    <?php if($this->error): ?>
        <div class="alert alert-success" role="alert">
            Oops! There was an error. Please contact the office.
        </div>
    <?php endif; ?>

    <form class="form" method="POST">
        <?php echo wp_nonce_field('handle_custom_form', 'nonce_custom_form'); ?>
        <input type="hidden" name="sub_19493" value="sub_11492"/>
        <p>
            <label><strong>Name</strong></label>
            <input type="text" name="lightpost_name" class="form-control">
        </p>
        <p>
            <label><strong>Email</strong></label>
            <input type="text" name="lightpost_email" class="form-control">
        </p>

    <?php
    $by_attendance_types = [];
    foreach($this->bible_classes[0]['classes'] as $class):
        
        $by_attendance_types[$class['attendance_type']['id']][] = $class;

    endforeach;
    ?>
    
    <?php if ($by_attendance_types): ?>
        <?php $current_type_id = null; ?>
        <?php foreach ($by_attendance_types as $type_id => $classes): ?>
            <?php if($current_type_id != $type_id): ?>
                <h2><?php echo esc_html($classes[0]['attendance_type']['name']); ?></h2>
                <table class="table table-condensed table-striped table-bordered" width="100%">
                <!-- <thead class="thead-dark table-sm">
                    <tr>
                        <th class="" align="left" width="5%">&nbsp</th>
                        <th class="pl-3" align="left">Title</th>
                        <th class="pl-3" align="left" width="15%">Location</th>
                    </tr>
                </thead> -->
                <tbody>
            <?php endif; ?>
                <?php foreach($classes as $class): ?>
                    <tr>
                        <td class="text-center">
                            <input type="radio" name="selected_classes[<?php echo esc_attr($type_id); ?>][]" value="<?php echo esc_attr($class['id']); ?>" id="class-<?php echo esc_attr($class['id']); ?>">
                        </td>
                        <td>
                            <label for="class-<?php echo esc_attr($class['id']); ?>">
                                <strong><?php echo esc_html($class['title']); ?></strong>
                            </label>
                        </td>
                        <td class="lightpost_bible_class_registration__table_passage">
                            <?php echo esc_html($class['location_name']); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php if($current_type_id != $type_id): ?>
                <tr>
                    <td class="text-center">
                        <input type="radio" name="selected_classes[<?php echo esc_attr($type_id); ?>][]" value="teaching" id="class-<?php echo esc_attr($class['id']); ?>-teaching">
                    </td>
                    <td>
                        <label for="class-<?php echo esc_attr($class['id']); ?>-teaching">
                            <strong>I am teaching.</strong>
                        </label>
                    </td>
                    <td></td>
                </tr>
                </tbody>
                </table>
                <?php $current_type_id = $type_id; ?>
            <?php endif; ?>
        <?php endforeach ?>
    <?php else: ?>
        <tr>
            <td class="lightpost_bible_class_registration__table_no_sermons_found" colspan="5">No Bible classes found.</td>
        </tr>
    <?php endif ?>

    <button class="form-control btn btn-primary" type="submit">Submit Registration</button>

    </form>
    <hr>
</div>
<div class="col-12 mt-4">
    <?php if ($by_attendance_types): ?>
        <?php $current_type_id = null; ?>
        <?php foreach ($by_attendance_types as $type_id => $classes): ?>
            <?php if($current_type_id != $type_id): ?>
                <h3><?php echo esc_html($classes[0]['attendance_type']['name']); ?></h3>
                <hr>
            <?php endif; ?>
                <?php foreach($classes as $class): ?>
                    <p>
                        <h4 class="font-weight-bold"><?php echo esc_html($class['title']); ?></h4>
                        <?php echo esc_html($class['description']); ?>
                        <?php if(is_array($class['teachers'])): ?>
                            <br>
                            <?php foreach($class['teachers'] as $teacher): ?>
                                <span class="badge badge-large badge-secondary py-1 px-2"><?php echo $teacher['first_name'] . ' ' . $teacher['last_name']; ?></span>
                            <?php endforeach;?>
                        <?php endif; ?>
                    </p>
                <?php endforeach; ?>
            <?php if($current_type_id != $type_id): ?>
                <br>
                <?php $current_type_id = $type_id; ?>
            <?php endif; ?>
        <?php endforeach ?>
    <?php endif ?>

</div>
</div>
</div>

</div>