<div class="col">
    <?php if ($dateIndex +1 > 1 ): ?>
        <a href="<?php echo isset($data['airfield_id']) ? $data['airfield_id'] . '/' . $dates[$dateIndex-1] . '?order_by=' . strtolower($data['order_by']) : $dates[$dateIndex-1] . '?order_by=' . $data['order_by'] ?>" class="btn btn-primary float-right">next</a>
    <?php else: ?>
        <a class="btn btn-primary float-right disabled">next</a>
    <?php endif; ?>
</div>


<div class="col">
    <?php if ($dateIndex +1 < count($dates)): ?>
        <a href="<?php echo isset($data['airfield_id']) ? $data['airfield_id'] . '/' . $dates[$dateIndex+1] . '?order_by=' . strtolower($data['order_by']) : $dates[$dateIndex+1] . '?order_by=' . $data['order_by'] ?>" class="btn btn-primary float-right">prev</a>
    <?php else: ?>
        <a class="btn btn-primary float-right disabled">prev</a>
    <?php endif; ?>
</div>

<div class="col">
    <h6 class="btn float-right"><?php echo str_replace('-', '‑', $data['show_date']); ?></h6>
</div>