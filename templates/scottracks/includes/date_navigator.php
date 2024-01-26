<div class="col">
    <?php if ($dateIndex +1 > 1 ): ?>
        <a href="<?php echo isset($data['airfield_id']) ? $data['airfield_id'] . '?date=' . $dates[$dateIndex-1] . '&order_by=' . strtolower($data['order_by']) : '?date=' . $dates[$dateIndex-1] . '&order_by=' . $data['order_by'] ?>" class="btn btn-primary float-right mr-1">next</a>
    <?php else: ?>
        <a class="btn btn-primary float-right disabled mr-1">next</a>
    <?php endif; ?>
</div>


<div class="col">
    <?php if ($dateIndex +1 < count($dates)): ?>
        <a href="<?php echo isset($data['airfield_id']) ? $data['airfield_id'] . '?date=' . $dates[$dateIndex+1] . '&order_by=' . strtolower($data['order_by']) : '?date=' . $dates[$dateIndex+1] . '&order_by=' . $data['order_by'] ?>" class="btn btn-primary float-right mr-1">prev</a>
    <?php else: ?>
        <a class="btn btn-primary float-right disabled mr-1">prev</a>
    <?php endif; ?>
</div>


<div class="col">
    <label id="datepicker-label" for="datepicker" class="float-right mr-1">
        <?php echo $data['show_date']; ?>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
        </svg>
    </label>
    <input id="datepicker" type="text" class="float-right" name="date" readonly="readonly" style="opacity: 0;width: 0">
</div>

<script>
    $(function() {
        $("#datepicker").datepicker({
            dateFormat: "yy-mm-dd",
            onSelect: function(dateText) {
                console.log(dateText);
                window.location.assign('?date=' + dateText);
            }
        });
    });
</script>
