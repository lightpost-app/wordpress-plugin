<link href="<?php echo plugins_url('css/lightpost.css', dirname(__FILE__)) ?>" rel="stylesheet">

<div class="lp-bootstrap">
    <div class="container p-0">
        <div class="row">
            <div class="col-12 text-center">
                <button class="btn btn-primary float-left d-none d-sm-block" onclick="window.history.back();">< Back</button>
                <h2>
                    <?php echo $this->family['user']['first_name']; ?>
                    <?php echo $this->family['user']['spouse'] ? ' & ' . $this->family['user']['spouse']['first_name'] : null; ?>
                    <?php echo $this->family['user']['last_name']; ?>
                </h2>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-lg-6">

                <table class="table">
                    <tr>
                        <td class="align-text-top text-center table-active" width="45">
                            <img src="<?php echo plugins_url('img/map-marked-alt.svg', dirname(__FILE__)) ?>" />
                        </td>
                        <td>
                            <?php if(isset($this->family['addresses'])): ?>
                                <?php foreach($this->family['addresses'] as $address): ?>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tbody>
                                        <tr>
                                            <td width="15%" class="text-right">
                                                Family:
                                            </td>
                                            <td class="pb-0 pl-0">
                                                <a class="btn btn-sm btn-outline-primary btn-sm p-0 m-0 float-right border-0" rel="tooltip" data-placement="left" title="Open Map" target="_blank" data-turbolinks="false" href="<?php echo $address['maps_link']; ?>">
                                                    <img height="90" width="90" src="<?php echo $address['maps_image']; ?>" class="border border-secondary"/>
                                                </a>
                                                
                                                <address>
                                                    <?php echo $address['address_html']; ?>
                                                    <br>
                                                    <a class="btn btn-sm btn-outline-primary btn-sm py-0" rel="tooltip" data-placement="left" title="Open Map" target="_blank" data-turbolinks="false" href="<?php echo $address['maps_link']; ?>">
                                                        <span class-old="d-none d-sm-inline d-md-none d-lg-inline">Directions</span>
                                                    </a>
                                                </address>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted text-center">
                                    <span class="badge badge-warning badge-large">No family address on file.</span>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-text-top text-center table-active" width="45">
                            <img src="<?php echo plugins_url('img/phone-volume.svg', dirname(__FILE__)) ?>" />
                        </td>
                        <td>
                            <?php if(isset($this->family['phones'])): ?>
                                <table class="table table-sm  table-borderless mb-0">
                                    <tbody>
                                        <?php foreach($this->family['phones'] as $phone): ?>
                                            <tr>
                                                <td width="15%" class="text-right">
                                                    <?php if($phone['family_id']): ?>
                                                        Family: 
                                                    <?php else: ?>
                                                        <strong><?php echo $phone['first_name']; ?>:</strong>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="tel:<?php echo $phone['number']; ?>">
                                                        <?php echo $phone['number_formatted']; ?>
                                                    </a>
                                                    <span class="badge badge-light"><?php echo $phone['type']; ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <span class="text-muted text-center">
                                    <span class="badge badge-warning badge-large">No family phone number on file.</span>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="align-text-top text-center table-active" width="45">
                            <img src="<?php echo plugins_url('img/envelope.svg', dirname(__FILE__)) ?>" />
                        </td>
                        <td>
                            <?php if(isset($this->family['emails'])): ?>
                                <table class="table table-sm table-borderless mb-0">
                                    <tbody>
                                        <?php foreach($this->family['emails'] as $email): ?>
                                            <tr>
                                                <td width="15%" class="text-right">
                                                    <?php if($email['family_id']): ?>
                                                        Family: 
                                                    <?php else: ?>
                                                        <strong><?php echo $email['first_name']; ?>:</strong>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="pl-0">
                                                    <a href="mailto:<?php echo $email['email']; ?>"><?php echo $email['email']; ?></a>
                                                    <span class="badge badge-light"><?php echo $email['type']; ?></span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <span class="text-muted text-center">
                                    <span class="badge badge-warning badge-large">No family email on file.</span>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php if(isset($this->family['user']['date_married'])): ?>
                        <tr>
                            <td class="align-text-top text-center table-active" width="45">
                                <img src="<?php echo plugins_url('img/heart.svg', dirname(__FILE__)) ?>" />
                            </td>
                            <td>
                                Married on <?php echo $this->family['user']['date_married']['date_formatted']; ?>
                                <small class="text-muted">
                                    <?php echo $this->family['user']['date_married']['diff_for_humans']; ?>
                                </small>
                            </td>
                        </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="align-text-top text-center table-active" width="45">
                            <img src="<?php echo plugins_url('img/home.svg', dirname(__FILE__)) ?>" />
                        </td>
                        <td>
                            <table class="table table-sm table-borderless table-striped m-0">
                                <thead class="thead-dark">
                                <tr class="text-muted">
                                    <th scope="col" class="">Name</th>
                                    <th scope="col" class="">Birthday</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($this->family['family_members'] as $member): ?>
                                    <tr>
                                        <td>
                                            <?php echo $member['first_name']; ?>
                                            <span style="font-size: 78%; color: #aaa;">
                                                <?php echo $member['family_role']; ?>
                                                <?php echo ($member['family_role'] && $member['is_baptized']) ? ' / ' : null; ?>
                                                <?php echo ($member['is_baptized']) ? 'baptized' : null; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="d-none d-sm-inline d-md-none d-lg-inline"><?php echo $member['birthdate']; ?></span>
                                            <span class="d-sm-none d-md-inline d-lg-none"><?php echo $member['birthdate']; ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>

            </div>
            <div class="col-12 col-lg-6 text-center">
                <br class="d-inline d-md-none">
                <?php if(isset($this->family['family_photo'])): ?>
                    <a href="<?php echo $this->family['family_photo']['full_size_url']; ?>" target="_blank">
                        <img src="<?php echo $this->family['family_photo']['thumbnail_retina_url']; ?>" srcset="<?php echo $this->family['family_photo']['thumbnail_retina_url']; ?> 1x, <?php echo $this->family['family_photo']['thumbnail_retina_url']; ?> 2x" class="rounded img-thumbnail" width="100%"/>
                    </a>
                <?php else: ?>
                    <span class="badge badge-large badge-light">No family photo on file.</span>
                <?php endif; ?>

            </div>
            <div class="col-12 col-md-6">
                <br class="d-inline d-md-none">

            </div>
        </div>
    </div>
</div>
