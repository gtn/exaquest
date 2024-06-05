define(['jquery'], function($)
{
	  var chrt = document.getElementById("myScatterChart").getContext("2d");
      var chartId = new Chart(chrt, {
         type: 'scatter',
         data: {
            labels: ["HTML", "CSS", "JAVASCRIPT", "CHART.JS", "JQUERY", "BOOTSTRP"],
            datasets: [{
               label: "online tutorial subjects",
               data: [
                  {x:10, y:14},
                  {x:25, y:35},
                  {x:21, y:20},
                  {x:35, y:28},
                  {x:15, y:10},
                  {x:19, y:30},
               ],
               backgroundColor: ['yellow', 'aqua', 'pink', 'lightgreen', 'gold', 'lightblue'],
               borderColor: ['black'],
               radius: 8,
            }],
         },
         options: {
            responsive: false,
            scales: {
               x: {
                  type: 'linear',
                  position: 'bottom,'
               }
            }
         },
      });
});