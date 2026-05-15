<?global $USER;?>
<?if($USER->IsAuthorized()) {?>
	<div class="visible-xs">
		<div class="col-xs-12">
			<span class="btn btn-transparent btn-xs wc showMobileLeftMenu">
				<i class="fa fa-bars"></i>
				<span>&nbsp;Меню</span>
			</span>
			<br><br>
		</div>
	</div>

	<script>
	$(document).ready(function() {
		$('html').on('click', '.showMobileLeftMenu', function() {
			$('aside.sidebar').css({'marginBottom':'30px', 'marginLeft':'16px', 'marginRight':'16px'}).slideToggle();
		})
	});
	</script>
<?}?>