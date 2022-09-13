<div class="col">
    <?php if ($data['next_day']): ?>
        <a href="<?php echo '/?date=' . $data['next_day'] ?>" class="btn btn-primary float-right">next</a>
    <?php else: ?>
        <a class="btn btn-primary float-right disabled">next</a>
    <?php endif; ?>
</div>


<div class="col">
        <a href="<?php echo '/?date=' . $data['previous_day'] ?>" class="btn btn-primary float-right">prev</a>
</div>

<div class="col">
    <h6 class="btn float-right"><?php echo str_replace('-', '‑', $data['date']); ?></h6>
</div>