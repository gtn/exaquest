document.addEventListener("DOMContentLoaded", function() {
	// Function to get a random element from an array
	function getRandomElement(arr) {
		if (arr.length === 0) return undefined; // Handle empty array
		const randomIndex = Math.floor(Math.random() * arr.length);
		return arr[randomIndex];
	}
    var e1 = document.getElementById("scatterChart");
	var srcelement = document.getElementsByClassName("chart-image")[0];
    e1.style.width = srcelement.offsetWidth;
    e1.style.height = srcelement.offsetHeight;
	e1.width = srcelement.offsetWidth;
	e1.height = srcelement.offsetHeight;
	
	e1.width = srcelement.clientWidth;
    e1.height = srcelement.clientHeight;
	
    var ctx = e1.getContext("2d");

    if (typeof window.chartData !== 'undefined' && typeof window.chartLabels !== 'undefined' && typeof window.chartLabel !== 'undefined' && typeof window.chartBackgroundColors !== 'undefined') {
		var graphdatasets = [];
		for (var i = 0; i < window.chartLabels.length; i++) {
			graphdatasets.push({
				label: window.chartLabels[i],
				data: [window.chartData[i]],
				backgroundColor: getRandomElement(window.chartBackgroundColors),
				pointStyle: getRandomElement(window.pointStyles),
				pointStyleWidth: 85,
				//borderColor: getRandomElement(window.chartBackgroundColors),
				borderWidth: 2,
				pointRadius: 10,
				pointHoverRadius: 15
			});
		}
        var scatterChart = new Chart(ctx, {
            type: "scatter",
            data: {
                //labels: window.chartLabels,
                datasets: graphdatasets
            },
            options: {
                responsive: true,				
				scales: {
					x: {
						type: 'linear',
						position: 'bottom',
						title: {
							display: true,
							text: '∅'+' Schwierigkeit'
						},
						min: 0,
						max: 140
					},
					y: {
						type: 'linear',
						title: {
							display: true,
							text: '∅'+' Trennschärfe'
						},
						min: 0,
						max: 140
					}
				},
				plugins: {
					legend: {
						display: true,
						position: 'bottom',						
						labels: {
							font: {
								size: 14
							},
							color: 'rgb(0, 0, 0)',
							usePointStyle: true,
							generateLabels: (chart) => {
								console.log(chart.data.datasets);
								return chart.data.datasets.map((dataset,index)=>({
									text:dataset.label,
									fillStyle: dataset.backgroundColor,
									pointStyle: dataset.pointStyle,
									hidden:false
								}))
							}
						}
					}
				},
			}
		});
    } else {
        console.error('No data provided for the chart.');
    }
});
