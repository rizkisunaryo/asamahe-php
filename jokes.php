
					<div class="row jokes" style="margin-left:10px; margin-right:10px;">
						<?php 
							if (!is_null($jokes['Jokes'])) {
							foreach ($jokes['Jokes'] as $arrayKey => $joke) {
							?>
						<div class="row" id="<?=$joke['JokeId']?>" style="padding:10px 5px;">
						<div style="background-color:white; border-top-left-radius:10px; border-top-right-radius:10px; padding:10px 20px 20px;">
							<?php
							if ($menu!='joker') {
								$picUrl='images/unknown.png';
								if ($joke['JokerPicUrl']!="") {
									$picUrl = $joke['JokerPicUrl'];
								}
								?>
							<div class="profPicHolder"><a href="joker.php?id=<?=$joke['Joker']?>"><img src="<?=$picUrl?>" style="height:60px; display:inline-block;"></a></div>
							<div style="display:inline-block;">
								<?php
								$jokerName='unknown';
								if ($joke['JokerName']!="") {
									$jokerName=$joke['JokerName'];
								}
								?>
								<span style="font-size:20px; font-weight:bold; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif; margin-left:10px;"><a href="joker.php?id=<?=$joke['Joker']?>" style="color:#1fd8ed"><?=$jokerName?></a></span><br />
								<span style="font-size:11px; font-weight:bold; color:#9197a3;"><!--?=date_format(date_create($joke['Time']),"d M - h:i a")?-->&nbsp;</span>
							</div>
							<br />
							<?php
							}
								$jokeTitle='untitled';
								if ($joke['Title']!="") {
									$jokeTitle=$joke['Title'];
								}
								?>
							<div style="font-weight:bold; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;">
								<a href="joke.php?id=<?=$joke['JokeId']?>" style="color:black; font-size:20px;"><?=$jokeTitle?></a>
								<?php
								if ($id==$joke['Joker']) {
								?>
								<a onclick="deleteJokeDialogFromList('<?=$key?>','<?=$id?>','<?=$joke[JokeId]?>','<?=$redirUrl?>')"><img class="img-responsive function-button" src="images/delete.png" alt="Chania"></a>
								<?php
								}
								?>
							</div>
							<div style="padding:0;">
								<span style="font-size:16px; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;"><?=$joke['Content']?></span>
							</div>
							<br />
							<span style="color:#1fd8ed; font-family:Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;"><span id="like-count_<?=$joke['JokeId']?>"><?=$joke['LikeCount']?></span> laughs&nbsp;&nbsp;&nbsp;&nbsp;·&nbsp;&nbsp;&nbsp;&nbsp;<a href="joke.php?id=<?=$joke['JokeId']?>" style="color:#71e2f2;"><?=$joke['CommentCount']?> comments</a></span><br />
						</div>
							<?php
							if ($id!='') {
							?>
						<div style="background-color:#d9d6d6; padding:5px 15px">
							<!-- <div style="border-radius:10px; border-style:solid; border-color:white; padding:5px; display:inline-block;"> -->
							<div style="display:inline-block;">
							<a onclick="toggleLike('<?=$key?>','<?=$joke[JokeId]?>','<?=$id?>')">
								<img class="img-responsive function-button like-btn_<?=$joke[JokeId]?>" 
								src="images/laugh.png" style="opacity:<?php echo $joke['IsLiked']==0? 0.4 : 1.0; ?>">
							</a>
							</div>
							<div style="display:inline-block; float:right;">
							<a onclick="report('<?=$key?>','<?=$joke[JokeId]?>','<?=$id?>')">
								<img class="img-responsive function-button report-btn_<?=$joke['JokeId']?>" 
								src="images/report.png" style="opacity:<?php echo $joke['IsReported']==0? 0.4 : 1.0; ?>">
							</a>
							</div>
						</div>
							<?php 
							}
							?>
						<!-- <hr /> -->
						</div>
						<?php 
							}
							}
							?>
					</div>
				