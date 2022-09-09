<div class="container col-sm-">
    <div class="row">
        <div class="col-sm">
            <ul class="list-group">
                <?php
                foreach ($data['flown_today'] as $id => $airfield):
                    $clean_url = url($id);
                    $url = sprintf('/airfields/%s', $clean_url);
                    ?>
                    <a href="<?php echo $url;?>" class="list-group-item list-group-item-action">
                        <?php echo ucwords(strtolower($airfield['name'])); ?>
                        <span class="badge badge-primary badge-pill"><?php echo $airfield['flights']; ?></span>
                    </a>
                <?php endforeach; ?>
                <?php
                foreach ($data['airfield_names'] as $id => $airfield_data):
                    if (!in_array($id, array_keys($data['flown_today']))):
                        $clean_url = url($id);
                        $url = sprintf('/airfields/%s', $clean_url); ?>
                        <a href="<?php echo $url;?>" class="list-group-item list-group-item-action">
                            <?php echo ucwords(strtolower($airfield_data['name']))?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</div>