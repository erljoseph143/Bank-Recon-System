
<script type="text/javascript">
    var es = new EventSource("<?php echo action('Controller@Action'); ?>");

    es.onmessage = function(e) {
        console.log(e);
    }
</script>