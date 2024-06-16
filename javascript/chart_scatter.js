document.addEventListener("DOMContentLoaded", function() {
    var e1 = document.getElementById("scatterChart");
    e1.style.width = document.getElementsByClassName("chart-image")[0].offsetWidth;
    e1.style.height = document.getElementsByClassName("chart-image")[0].offsetHeight;
    var ctx = e1.getContext("2d");

    if (typeof window.chartData !== 'undefined' && typeof window.chartLabels !== 'undefined' && typeof window.chartLabel !== 'undefined' && typeof window.chartBackgroundColors !== 'undefined') {
        var scatterChart = new Chart(ctx, {
            type: "scatter",
            data: {
                labels: window.chartLabels,
                datasets: [
					{
						label: window.chartLabel,
						data: window.chartData,
						backgroundColor: window.chartBackgroundColors,
						hoverOffset: 5,
						pointStyle: ["rect", "circle", "rectRounded","triangle"],
					}
				]
            },
            options: {
                responsive: true,				
				scales: {
					x: {
						type: 'linear',
						position: 'bottom',
						title: {
							display: true,
							text: 'Schwierigkeit'
						}
					},
					y: {
						type: 'linear',
						title: {
							display: true,
							text: 'Trennschärfe'
						}
					}
				}
			}
		});
    } else {
        console.error('No data provided for the chart.');
    }
});
