<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<body>
	

<div id="container">
	<div id="mitborder"> MIT </div>
	<div class="row">
		<div class="col-md-2"></div>
		<div class="col-md-8 text-center welcometext">
			<p>Welcome</p>
			<p>To</p>
			<p>MIT</p>
		</br>
		<p>MIT Inventory Management System</p>

		</div>
		<div class="col-md-2"></div>
	</div>


</div>

</body>

<script type="text/javascript">
	$(document).ready(function () {
    // Handler for .ready() called.
    window.setTimeout(function () {
        location.href = "<?php echo base_url();?>login";
    }, 3000);
});
</script>
</html>