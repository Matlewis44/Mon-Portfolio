<?php
session_start();
require_once 'mesFonctions.php';
if(isset($_SESSION['groupe'])){
	$groupe=$_SESSION['groupe'];
	if(strcmp($groupe,"dev")==0 or strcmp($groupe,"usr")==0){
		initMenu("<a href='accueil.php'>Accueil</a> > Analytique");
		
		echo"<link rel='stylesheet' type='text/css' href='css&fonts/feuillestyle4.css' />";
		echo "<h2 style='padding-left: 30px; color:#404040;'>| Analytique |</h2>";
		echo "<div id='configuration-systeme'>";

		echo "<h3>Dernières 48h</h3>";
		
		$abs = array_reverse (getLabels());
		$ord = array_reverse (dbToArray());
		$dates = array_reverse (getDates());

		echo"<canvas id='myChart' canvas.width='50' canvas.heigth='50'></canvas>";
		
		?>
		<script>
			
			<?php echo "var labels = '".implode("<>", $abs)."'.split('<>');"; ?>
			<?php echo "var valeurs = '".implode("<>", $ord)."'.split('<>');"; ?>
			<?php echo "var dates = '".implode("<>", $dates)."'.split('<>');"; ?>
			var ctx = document.getElementById('myChart').getContext('2d');
			var chart = new Chart(ctx, {
				// The type of chart we want to create
				type: 'line',

				// The data for our dataset
				data: {
					labels: labels,
					datasets: [{
						label: 'humidité',
						borderColor: 'rgb(48, 165, 255)',
						fill: false,
						spanGaps: false,
						
						data: valeurs
					}]
				},

				// Configuration options go here
				options: {
					scales: {
						yAxes: [{
							display: true, 
							stacked: true, 
							ticks: { min: 0, max: 100},
							scaleLabel: {
								display: true,
								labelString: 'Humidité en %'
							}
						}]
					},
					tooltips: {
						callbacks: {
							title: function(tooltipItem, data) {
								var title = '';
								title += dates[tooltipItem[0].index];
								title += ' ';
								if(dates[tooltipItem[0].index] == data.labels[tooltipItem[0].index]){
									title += '00h00';
								}
								else{
									title += data.labels[tooltipItem[0].index];
								}
								return title;
							},
							label: function(tooltipItem, data) {
								var label = data.datasets[tooltipItem.datasetIndex].label || '';

								if (label) {
									label += ' : ';
								}
								label += data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
								
								label += '%';
								
								return label;
							}
						}
					}
				}
				
			});
			
			
		</script>
		<?php
		echo "</div>";
		initFin();
	}
	else
		header('Location: index.php?error=403');
}
else
	header('Location: index.php?error=401');
	
?>
