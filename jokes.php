
					<div class="row jokes" style="margin-left:10px; margin-right:10px;">
						<?php 
							if (!is_null($jokes['Jokes'])) {
							foreach ($jokes['Jokes'] as $arrayKey => $joke) {
							?>
						<div class="row" style="padding:5px;">
							<?php
							if ($menu!='joker') {
								$picUrl='images/unknown.png';
								if ($joke['JokerPicUrl']!="") {
									$picUrl = $joke['JokerPicUrl'];
								}
								?>
							<a href="joker.php?id=<?=$joke['Joker']?>"><img src="<?=$picUrl?>" style="height:60px; display:inline-block; margin-bottom:10px;"></a>
							<div style="display:inline-block;">
								<?php
								$jokerName='unknown';
								if ($joke['JokerName']!="") {
									$jokerName=$joke['JokerName'];
								}
								?>
								<span style="font-size:14px; font-weight:bold; color:blue;"><a href="joker.php?id=<?=$joke['Joker']?>"><?=$jokerName?></a></span><br />
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
							<div style="font-size:18px; font-weight:bold;">
								<a href="joke.php?id=<?=$joke['JokeId']?>"><?=$jokeTitle?></a>
								<?php
								if ($id==$joke['Joker']) {
								?>
								<a onclick="deleteJokeDialog('<?=$key?>','<?=$id?>','<?=$joke[JokeId]?>','<?=$redirUrl?>')"><img class="img-responsive function-button" src="images/delete.png" alt="Chania"></a>
								<?php
								}
								?>
							</div>
							<div style="padding:0 10px; padding-bottom:0px;">
								<span style="font-size:14px;"><?=$joke['Content']?></span>
							</div>
							<br />
							<span><span id="like-count_<?=$joke['JokeId']?>"><?=$joke['LikeCount']?></span> laughs · <a href="joke.php?id=<?=$joke['JokeId']?>"><?=$joke['CommentCount']?> comments</a></span><br />
							<?php
							if ($id!='') {
							?>
							<span>
							<a onclick="toggleLike('<?=$key?>','<?=$joke[JokeId]?>','<?=$id?>')">
								<img class="img-responsive function-button like-btn_<?=$joke[JokeId]?>" 
								src="images/laugh.png" style="opacity:<?php echo $joke['IsLiked']==0? 0.4 : 1.0; ?>">
							</a>
							<a onclick="report('<?=$key?>','<?=$joke[JokeId]?>','<?=$id?>')">
								<img class="img-responsive function-button report-btn_<?=$joke['JokeId']?>" 
								src="images/report.png" style="opacity:<?php echo $joke['IsReported']==0? 0.4 : 1.0; ?>">
							</a>
							</span>
							<?php 
							}
							?>
						</div>
						<hr />
						<?php 
							}
							}
							?>
					</div>
				