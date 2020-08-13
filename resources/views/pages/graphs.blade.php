@extends('master')

@section('title')
Grafieken
@endsection


@section('content')

<h1>Grafieken</h1>

<hr />

<ul class="nav nav-tabs">
    <li role="presentation" class="active"><a href="#stats" role="tab" data-toggle="tab">Statistieken</a></li>
    <li role="presentation"><a href="#registrations" role="tab" data-toggle="tab">Inschrijvingen</a></li>
    <li role="presentation"><a href="#camp-prefs" role="tab" data-toggle="tab">Kampvoorkeur deelnemers</a></li>
    <li role="presentation"><a href="#registration-days" role="tab" data-toggle="tab">Inschrijvingen dagen voor
            kamp</a></li>
</ul>

<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="stats">

        <p>
            <br />
            NB: pas vanaf 1 augustus wordt het huidige Anderwijsjaar meegenomen in de onderstaande grafieken. Eerder
            geeft de grafiek dan geen goed beeld.
        </p>

        <p>
            Wil je een nieuwe grafiek in dit rijtje? Vraag het even lief aan een AAS-baas. En: hoe specifieker je
            verzoek, hoe beter.
        </p>

        <div class="row">
            <div class="col-sm-12">
                <div id="memb_growth_chart" style="height:400px;"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div id="part_growth_chart" style="height:400px;"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div id="perc_new_chart" style="height:400px;"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div id="ave_num_camps_chart" style="height:400px;"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div id="ave_num_per_camp_chart" style="height:400px;"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div id="memb_part_ratio_chart" style="height:400px;"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div id="male_female_ratio_chart" style="height:400px;"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div id="trainer_growth_chart" style="height:400px;"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div id="ave_num_trainings_chart" style="height:400px;"></div>
            </div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="registrations">
        <div class="row">
            <div class="col-sm-12">
                <div id="registrations_chart" style="width:100%; height:600px; margin-top:20px;"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <h3>Sharell-knoppen</h3>
                <button class="btn btn-primary" id="btnAllSeriesOn">Alles aan</button>
                <button class="btn btn-primary" id="btnAllSeriesOff">Alles uit</button>
            </div>
        </div>
    </div>

    <div role="tabpanel" class="tab-pane" id="camp-prefs">
        <p><br />Sinds begin 2018 wordt in de deelnemerenquête gevraagd in welke periode ze het liefst op kamp gaan.
            Dit wordt in onderstaande grafiek weergegeven.</p>

        <p><b>Aantal deelnemers dat deze vraag heeft beantwoord: {{ $prefs_review_count }}</b></p>

        <div id="camp_preference_chart"></div>

    </div>

    <div role="tabpanel" class="tab-pane" id="registration-days">

        <div class="row">
            <div class="col-sm-12">
                <div id="avg-days-before-event" style="height:400px;"></div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('footer')
<!-- Load the Google Charts API and the HighCharts API -->
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript">
    // Load the Visualization API and the piechart package.
  google.load('visualization', '1.0', {'packages':['corechart']});
  google.setOnLoadCallback(drawCharts);

  function drawCharts() {

    // Set point size for all graphs
    var pointSize = 8;

    // Member growth per year
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($data['membGrowth']); ?>);

    var options = {
          title: 'Leiding op kamp',
          vAxis: {minValue : 0},
          legend: { position: 'bottom' },
          pointSize: pointSize
        };

    var chart = new google.visualization.LineChart(document.getElementById('memb_growth_chart'));
    chart.draw(data, options);
    
    // Participant growth per year
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($data['partGrowth']); ?>);

    var options = {
          title: 'Deelnemers op kamp',
          vAxis: {minValue : 0},
          legend: { position: 'bottom' },
          pointSize: pointSize
        };

    var chart = new google.visualization.LineChart(document.getElementById('part_growth_chart'));
    chart.draw(data, options);
    
    // Percentage of new members and participants per year
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($data['percNew']); ?>);

    var options = {
          title: 'Percentage nieuwe leiding/deelnemers',
          colors: ['purple', 'cyan'],
          vAxis: {minValue : 0},
          legend: { position: 'bottom' },
          pointSize: pointSize
        };

    var chart = new google.visualization.LineChart(document.getElementById('perc_new_chart'));
    chart.draw(data, options);
    
    // Member to participant ratio
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($data['membPartRatio']); ?>);

    var options = {
          title: 'Leiding-deelnemer ratio op kamp',
          colors: ['green'],
          legend: { position: 'none' },
          pointSize: pointSize
        };

    var chart = new google.visualization.LineChart(document.getElementById('memb_part_ratio_chart'));
    chart.draw(data, options);
    
    // Average number of camps per member/participant per year
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($data['aveNumCamps']); ?>);

    var options = {
          title: 'Gemiddeld aantal kampen per persoon',
          colors: ['purple', 'cyan'],
          vAxis: {minValue : 1},
          legend: { position: 'bottom' },
          pointSize: pointSize
        };

    var chart = new google.visualization.LineChart(document.getElementById('ave_num_camps_chart'));
    chart.draw(data, options);

    // Average number of members and participants per camp
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($data['aveNumPerCamp']); ?>);

    var options = {
          title: 'Gemiddeld aantal leiding en deelnemers per kamp',
          colors: ['purple', 'cyan'],
          vAxis: {minValue : 1},
          legend: { position: 'bottom' },
          pointSize: pointSize
        };

    var chart = new google.visualization.LineChart(document.getElementById('ave_num_per_camp_chart'));
    chart.draw(data, options);
    
    // Male to female members and participants on camp ratio
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($data['maleFemaleRatio']); ?>);

    var options = {
          title: 'Ratio geslacht op kamp (M/V)',
          colors: ['purple', 'cyan'],
          legend: { position: 'bottom' },
          pointSize: pointSize
        };

    var chart = new google.visualization.LineChart(document.getElementById('male_female_ratio_chart'));
    chart.draw(data, options);
    
    // Trainer growth per year
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($data['trainerGrowth']); ?>);

    var options = {
          title: 'Trainers op training',
          vAxis: {minValue : 0},
          legend: { position: 'bottom' },
          pointSize: pointSize
        };

    var chart = new google.visualization.LineChart(document.getElementById('trainer_growth_chart'));
    chart.draw(data, options);

    // Average number of trainings per member per year
    var data = google.visualization.arrayToDataTable(<?php echo json_encode($data['aveNumTrainings']); ?>);

    var options = {
          title: 'Gemiddeld aantal trainingen per trainer',
          colors: ['black'],
          vAxis: {minValue : 1},
          legend: { position: 'none' },
          pointSize: pointSize
        };

	var chart = new google.visualization.LineChart(document.getElementById('ave_num_trainings_chart'));
	chart.draw(data, options);

    // Participants' preference for camp type (based on reviews)
    drawGoogleChart({
        element: "camp_preference_chart", 
        rawData: {!! json_encode($camp_prefs) !!}, 
        columns: [
            { id: "option", label: "Optie", type: "string" },
            { id: "votes", label: "Aantal", type: "number" },
        ],
        chartType: "BarChart",
        chartOptions: {
            title: "Kampvoorkeur uit deelnemerenquêtes",
            hAxis: {
                title: 'Aantal stemmen',
                minValue: 0,
            },
            vAxis: {
                title: 'Soort kamp',
            },
            legend: {
                position: 'none'
            }
        }
    });

    // Average number of days between registering and camp (both members and participants)
    drawGoogleChart({
        element: "avg-days-before-event", 
        rawData: {!! json_encode($avg_days_before_event) !!}, 
        columns: [
            { id: "code", label: "Kamp", type: "string" },
            { id: "avg_participants_days", label: "Deelnemers", type: "number" },
            { id: "naam", label: "Kamp", type: "string", role: "tooltip" },
            { id: "avg_members_days", label: "Leiding", type: "number" },
            { id: "naam", label: "Kamp", type: "string", role: "tooltip" },

        ],
        chartType: "ColumnChart",
        chartOptions: {
            title: "Gemiddeld aantal dagen tussen registratie en kamp",
            hAxis: {
                title: 'Kamp',
                minValue: 0,
                slantedText: true
            },
            vAxis: {
                title: 'Dagen voor kamp',
            }
        }
    });
  }
  
  function transformDataset(rawData, colOptions) {
      var cols = colOptions || Object.keys(rawData[0].map(function(colName) {
          return { id: colName, label: colName };
      }));

      var rows = rawData.map(function(rowObject) {
          return cols.map(function(column) {
              return rowObject[column.id];
          });
      });
      
      return [].concat([cols], rows);
  }

    function drawGoogleChart(options) {
        var data = transformDataset(options.rawData, options.columns);
        var dt = google.visualization.arrayToDataTable(data);
        var chart = new google.visualization[options.chartType || "LineChart"](document.getElementById(options.element));
        chart.draw(dt, options.chartOptions);
    }

  // Rerun the drawing of charts when window size changes
  $(window).resize(function() {
      drawCharts();
  });
  
  // Rerun the drawing of charts when an anchor is clicked
  $("a").click(function(e) {
     setTimeout(function(x) {drawCharts()}, 25); 
  });
  
  // The registration analysis is done with HighCharts (allows enabling and disabling of series)
  $("#registrations_chart").highcharts({
      chart: {
          type: 'line'
      },
      title: {
          text: 'Deelnemerinschrijvingen'
      },
      xAxis: {
          title: {
              text: 'Aantal dagen voor kamp'
          }
      },
      yAxis: {
          title: {
              text: 'Aantal deelnemers'
          }
      },
      series: <?php echo json_encode($registration_series); ?>
  });

  // Buttons for highcharts to show or hide all series
  $("#btnAllSeriesOn").click(function() {
    var hc = $("#registrations_chart").highcharts();
    $(hc.series).each(function() {
        this.setVisible(true, false);
    });
    hc.redraw();
  });

  $("#btnAllSeriesOff").click(function() {
    var hc = $("#registrations_chart").highcharts();
    $(hc.series).each(function() {
        this.setVisible(false, false);
    });
    hc.redraw();
  });
</script>
@endsection