jQuery(document).ready(function($) {
  console.log('Travel Map läuft');
  // var map;
  // function initMap() {
  //   map = new google.maps.Map(document.getElementById('travel-map'), {
  //     center: {lat: 30.00, lng: 4.00},
  //     zoom: 2
  //   });
  // }
  // initMap();
  google.charts.load('upcoming', {'packages':['geochart']});
  google.charts.setOnLoadCallback(drawRegionsMap);

  function drawRegionsMap() {
    var data = google.visualization.arrayToDataTable([
      ['Country'],
      ['Germany'],
      ['Italy'],
      ['Brazil'],
      ['Peru'],
      ['Bolivia'],
      ['Chile'],
      ['Argentina'],
      ['Paraguay'],
      ['US'],
      ['Japan'],
      ['Turkey'],
      ['Tunisia'],
      ['HKG'],
      ['Denmark'],
      ['France'],
      ['China'],
      ['Austria'],
      ['Croatia']
    ]);
    var options = {};

    var chart = new google.visualization.GeoChart(document.getElementById('travel-map'));
    chart.draw(data, options);
  }
});
