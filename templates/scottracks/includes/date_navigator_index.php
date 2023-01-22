
<div class="col">
    <?php if ($data['next_day']): ?>
        <a href="<?php echo '/?date=' . $data['next_day'] ?>" class="btn btn-primary float-right mr-1">next</a>
    <?php else: ?>
        <a class="btn btn-primary float-right disabled mr-1">next</a>
    <?php endif; ?>
</div>


<div class="col">
        <a href="<?php echo '/?date=' . $data['previous_day'] ?>" class="btn btn-primary float-right mr-1">prev</a>
</div>

<div class="col">
    <label id="datepicker-label" for="datepicker" class="float-right mr-1">
        <?php echo $data['date']; ?>
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar" viewBox="0 0 16 16">
            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
        </svg>
    </label>
    <input id="datepicker" type="text" class="float-right" name="date" style="opacity: 0;width: 0">
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
