
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Position Motor</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="assets/images/logo.png">
  <meta name="apple-mobile-web-app-title" content="C-IoT">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <link rel="shortcut icon" sizes="196x196" href="assets/images/logo.png">

  <!-- style -->
  <link rel="stylesheet" href="assets/animate.css/animate.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/glyphicons/glyphicons.css" type="text/css" />
  <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="assets/material-design-icons/material-design-icons.css" type="text/css" />

  <link rel="stylesheet" href="assets/bootstrap/dist/css/bootstrap.min.css" type="text/css" />
  <!-- build:css ../assets/styles/app.min.css -->
  <link rel="stylesheet" href="assets/styles/app.css" type="text/css" />
  <!-- endbuild -->
  <link rel="stylesheet" href="assets/styles/font.css" type="text/css" />
  <!-- including ECharts file -->
    <script src="https://cdn.jsdelivr.net/npm/echarts@5.0.1/dist/echarts.min.js"></script>
</head>
<body>
  <div class="app" id="app">

<!-- ############ LAYOUT START-->

  <!-- aside -->
  <div id="aside" class="app-aside modal nav-dropdown">
  	<!-- fluid app aside -->
    <div class="left navside dark dk" data-layout="column">
  	  <div class="navbar no-radius">
        <!-- brand -->
        <a class="navbar-brand">
        	<div ui-include="'assets/images/logo.svg'"></div>
        	<img src="assets/images/logo.png" alt="." class="hide">
        	<span class="hidden-folded inline">C-IoT</span>
        </a>
        <!-- / brand -->
      </div>
      <div class="hide-scroll" data-flex>
          <nav class="scroll nav-light">

              <ul class="nav" ui-nav>
                <li class="nav-header hidden-folded">
                  <small class="text-muted">Main</small>
                </li>

                <li>
                  <a href="position-motor.php" >
                    <span class="nav-icon">
                      <i class="material-icons">&#xe3fc;
                        <span ui-include="'assets/images/i_0.svg'"></span>
                      </i>
                    </span>
                    <span class="nav-text">Dashboard</span>
                  </a>
                </li>

              </ul>
          </nav>
      </div>
      <div class="b-t">
        <div class="nav-fold">

        </div>
      </div>
    </div>
  </div>
  <!-- / -->

  <!-- content -->
  <div id="content" class="app-content box-shadow-z0" role="main">
    <div class="app-header white box-shadow">
        <div class="navbar navbar-toggleable-sm flex-row align-items-center">
            <!-- Open side - Naviation on mobile -->
            <div class="collapse navbar-collapse" id="collapse">
              <p style="margin-top: 14px;">Yarib IMT</p>
            </div>
            <a data-toggle="modal" data-target="#aside" class="hidden-lg-up mr-3">
              <i class="material-icons">&#xe5d2;</i>
            </a>
            <!-- / -->

            <!-- Page title - Bind to $state's title -->
            <div class="mb-0 h5 no-wrap" ng-bind="$state.current.data.title" id="pageTitle"></div>


            <!-- / navbar right -->
        </div>
    </div>
    <div class="app-footer">
      <div class="p-2 text-xs">
        <div class="pull-right text-muted py-1">
          &copy; Copyright <strong>Flatkit</strong> <span class="hidden-xs-down">- Built with Love v1.1.3</span>
          <a ui-scroll-to="content"><i class="fa fa-long-arrow-up p-x-sm"></i></a>
        </div>
        <div class="nav">
        </div>
      </div>
    </div>
    <div ui-view class="app-body" id="view">


<!-- ############ PAGE START-->
<div class="padding">
    <div class="row">

      <div class="col-sm-6">
        <div class="box">
          <div class="box-header">
            <h3>Motor's Shaft Position</h3>
          </div>
          <div class="box-body">

            <!-- preparing a DOM with width and height for ECharts -->
              <div id="main" style="width:400px; height:310px; align-content: center;"></div>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="box">
          <div class="box-header">
              <h3>Options</h3>
          </div>
          <div class="box-body">
            <p>Position</p>
            <form class="p-x-xs">

              <div class="col-sm-10">
                <div class="input-group">
                  <input id="position_option" type="text" class="form-control" placeholder="180°">
                    <span class="input-group-btn">
                      <button onclick="command('set_pos')" class="btn dark" type="button">Set!</button>
                    </span>
                </div>
              </div>
            </form>
            <br>
              <p>PID Constants</p>
            <form class="p-x-xs">
              <div class="col-sm-10">
                  <input id="k_constant" type="text" class="form-control" placeholder="K">
              </div>
              <div class="col-sm-10">
                  <input id="d_constant" type="text" class="form-control" placeholder="D">
              </div>
              <div class="col-sm-10">
                  <input id="i_constant" type="text" class="form-control" placeholder="I">
              </div>
              <br>
              <div class="col-sm-10">
                <button onclick="command('change_kdi')" class="btn btn-fw dark" type="button">Change</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

<!-- ############ PAGE END-->

<!-- build:js scripts/app.html.js -->
<!-- jQuery -->
  <script src="libs/jquery/jquery/dist/jquery.js"></script>
<!-- Bootstrap -->
  <script src="libs/jquery/tether/dist/js/tether.min.js"></script>
  <script src="libs/jquery/bootstrap/dist/js/bootstrap.js"></script>
<!-- core -->
  <script src="libs/jquery/underscore/underscore-min.js"></script>
  <script src="libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js"></script>
  <script src="libs/jquery/PACE/pace.min.js"></script>

  <script src="html/scripts/config.lazyload.js"></script>

  <script src="html/scripts/palette.js"></script>
  <script src="html/scripts/ui-load.js"></script>
  <script src="html/scripts/ui-jp.js"></script>
  <script src="html/scripts/ui-include.js"></script>
  <script src="html/scripts/ui-device.js"></script>
  <script src="html/scripts/ui-form.js"></script>
  <script src="html/scripts/ui-nav.js"></script>
  <script src="html/scripts/ui-screenfull.js"></script>
  <script src="html/scripts/ui-scroll-to.js"></script>
  <script src="html/scripts/ui-toggle-class.js"></script>

  <script src="html/scripts/app.js"></script>
  <!-- ajax -->
  <script src="libs/jquery/jquery-pjax/jquery.pjax.js"></script>
  <script src="html/scripts/ajax.js"></script>
<!-- endbuild -->
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
<script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
<script type="text/javascript">

// based on prepared DOM, initialize echarts instance
        var myChartE = echarts.init(document.getElementById('main'));

        // specify chart configuration item and data
        option = {
            series: [{
                type: 'gauge',
                startAngle:0,
                endAngle:360,
                min:0,
                max:360,
                splitNumber:12,
                axisLine: {
                    lineStyle: {
                        width: 10,
                        color: [[1, '#37a2da']]
                    }
                },
                pointer: {
                    itemStyle: {
                        color: 'auto'
                    }
                },
                axisTick: {
                    distance: -30,
                    length: 8,
                    lineStyle: {
                        color: '#fff',
                        width: 2
                    }
                },
                splitLine: {
                    distance: -30,
                    length: 30,
                    lineStyle: {
                        color: '#fff',
                        width: 4
                    }
                },
                axisLabel: {
                    color: 'auto',
                    distance: 20,
                    fontSize: 15
                },
                detail: {
                    valueAnimation: true,
                    formatter: '{value}°',
                    color: 'auto'
                },
                data: [{
                    value: 180
                }]
            }]
        };
        // use configuration item and data specified to show chart
        myChartE.setOption(option);

var pos_value = 190;
var device_serie = "123456789";


/*
******************************
****** PROCESOS  *************
******************************
*/


function command(action){

  let pos_option = document.getElementById("position_option").value;
  let k_const = document.getElementById("k_constant").value;
  let d_const = document.getElementById("d_constant").value;
  let i_const = document.getElementById("i_constant").value;

  if(action == "set_pos"){
    client.publish(device_serie + "/pos", pos_option, (error) => {
      console.log(error || 'Position changed!!!')
      console.log(pos_option)
    });
  }
  if(action == "change_kdi"){
    client.publish(device_serie + "/constants", k_const +","+d_const+","+i_const, (error) => {
      console.log(error || 'Constants changed!!!')
    });
  }
}

function process_msg(topic, message){
  var msg = message.toString();
  var splitted_topic = topic.split("/");
  var serial_number = splitted_topic[0];
  var query = splitted_topic[1];

  if (query == "pos_value"){
    pos_value = msg;
    option.series[0].data[0].value=pos_value;
    myChartE.setOption(option, true);
  }
}

/*
******************************
****** CONEXION  *************
******************************
*/

// connect options
const options = {
      connectTimeout: 4000,
      // Authentication
      clientId: 'iotpos',
      username: 'web_client',
      password: '121212',
      keepalive: 60,
      clean: true,
}

    var connected = false;

    // WebSocket connect url
    const WebSocket_URL = 'wss://c-iot.ml:8094/mqtt'

    const client = mqtt.connect(WebSocket_URL, options)

    client.on('connect', () => {
        console.log('Mqtt conectado por WS! Exito!')
        client.subscribe(device_serie +'/pos_value', { qos: 0 }, (error) => {})
    })

    client.on('message', (topic, message) => {
      console.log('Mensaje recibido bajo tópico: ', topic, ' -> ', message.toString());
      process_msg(topic, message);
    })

    client.on('reconnect', (error) => {
        console.log('Error al reconectar', error)
    })

    client.on('error', (error) => {
        console.log('Error de conexión:', error)
    })

    $(document).ready(function() {
      $(window).keydown(function(event){
        if(event.keyCode == 13) {
          event.preventDefault();
          return false;
        }
      });
    });
</script>
</body>
</html>
