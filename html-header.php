<script type="text/javascript">
$(document).ready(function() {
$(".search-input").keyup(function(event){
    if(event.keyCode == 13){
        submitSearch();
    }
});
});

function submitSearch() {
	var searchVal = $('.search-input').val();
	searchVal = searchVal.replace(/\s/g, "+")
	window.location='<?=DOMAIN_URL?>search.php?keywords='+searchVal;
}

</script>
	</head>
	<body style="background-color:#efefef">
		<div id="fb-root"></div>
		<script>
(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=1554892734776831&version=v2.0";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
		</script>
		<div class="container-fluid">
			<div class="row kepala" style="background-color:#ff8b0f; border-radius:10px; box-shadow: 0 5px 5px #bababa; left:0; margin:10px; position:fixed; width:1257px; z-index:100;">
				<div class="col-sm-2">
					<div>
						<span style="font-size:30px; color:white;"><span style="font-weight:bold;">asamahe</span></span>
						<span style="font-size:20px; color:white;">(beta)</span>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="row">
						<center>
							<a href="<?=DOMAIN_URL?>" type="button" class="btn btn-info icon" data-toggle="tooltip" data-placement="bottom" title="recent jokes">
							<img class="img-responsive<?php if($menu!='newjokes') echo ' smaller-menu';?>" src="images/new.png" alt="Chania" >
							</a>
							<a href="hotjokes.php" type="button" class="btn btn-danger icon" data-toggle="tooltip" data-placement="bottom" title="hot jokes">
							<img class="img-responsive<?php if($menu!='hotjokes') echo ' smaller-menu';?>" src="images/hot-jokes.png" alt="Chania">
							</a>
							<a href="topjokers.php" type="button" class="btn btn-success icon" data-toggle="tooltip" data-placement="bottom" title="top jokers">
							<img class="img-responsive<?php if($menu!='topjokers') echo ' smaller-menu';?>" src="images/silly.png" alt="Chania">
							</a>
							<a href="createjoke.php" type="button" class="btn btn-primary icon" data-toggle="tooltip" data-placement="bottom" title="create new joke">
							<img class="img-responsive<?php if($menu!='createjoke') echo ' smaller-menu';?>" src="images/joking.png" alt="Chania">
							</a>
						</center>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="text-right">
					<form class="form-inline" role="form" style="display:inline-block; margin: 10px 30px 0 0">
						<div class="form-group">
							<label class="sr-only" for="search">Search</label>
							<input type="search" class="form-control search-input" id="search-textfield" placeholder="Search" style="width:200px; display:inline-block;">
							<a href="#" onclick="submitSearch()">
								<img src="images/search.png" style="display:inline-block;" class="search-btn">
							</a>
						</div>
					</form>
					<p class="text-right" style="margin-top:5px; color:white; font-weight:bold; display:inline-block;">
						<?php 
							if ($name!='') {
								echo '<a style="color:white;" href="joker.php?id='.$id.'">'.$name."</a> | ";
								echo '<a style="color:white;" href="logout.php?'.$logoutParams.'">Log out</a>';
							} else {
								echo '<a style="color:white;" href="'.$loginUrl.'">Login</a>';
							}
						?>
					</p>
					</div>
				</div>
			</div>