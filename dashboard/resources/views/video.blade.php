@extends('layouts.app')

@section('title', config('app.name') . ' | ' . $video->description)
@section('css')
<style>
.video-container {
  max-width: 600px;
  margin: auto;
}
.demo-placeholder,
a {
    -khtml-user-select: none;
    -o-user-select: none;
    -moz-user-select: none;
    -webkit-user-select: none;
    user-select: none;
  }
#plot-chart {
    height: 400px;
}
.video-placeholder.demo-placeholder {
  height: 200px;
}
#attention-bar {
  background-color: orange;
}
#happiness-bar {
  background-color: green;
}
#neutral-bar {
  background-color: lightgray;
}
#anger-bar {
  background-color: red;
}
#contempt-bar {
  background-color: brown;
}
#disgust-bar {
  background-color: darkgreen;
}
#surprise-bar {
  background-color: pink;
}
#fear-bar {
  background-color: purple;
}
#sadness-bar {
  background-color: darkblue;
}
.tab-bar {
  padding: 15px;
  text-align: center;
}
.tab-bar ul {
  list-style: none;
  list-style-type: none;
  margin: auto;
  padding: 0;
}
.tab-bar ul li {
  display: inline-block;
}
a.tab-button {
    padding: 10px;
    cursor: pointer;
}
a.tab-button.active {
  background: #efefef;
}
.dashboard_graph {
  overflow: hidden;
}
</style>
@endsection

@section('content')
<!-- top tiles -->
<div class="row tile_count">
<div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-user"></i> Total reactions</span>
  <div class="count" id="max-attentive"><?=$video->get_total_reactions()?></div>
</div>
<div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-user"></i> Max view count</span>
  <div class="count" id="avg-happiness-index"><?=$video->get_total_views()?></div>
</div>
<div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-clock-o"></i> Total comments</span>
  <div class="count" id="avg-attention-index"><?=count($video->comments)?></div>
</div>
<div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-user"></i> Average Comment sentiment</span>
  @if(($acs = $video->get_avg_sentiment()) < 50)
  <div class="count red" id="common-sentiment">Disapproval</div>
  <span class="count_bottom" id="sentiment-percentage"><i class="red"><?=$acs?>% </i></span>
  @else
  <div class="count green" id="common-sentiment">Approval</div>
  <span class="count_bottom" id="sentiment-percentage"><i class="green"><?=$acs?>% </i></span>
  @endif
</div>
</div>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="dashboard_graph">
    <div class="video-container">
      <video
          id="video"
          class="video-active"
          width="100%"
          style="display:block;width:100%;max-height: 500px"
          controls="controls">
          <source src="<?=$video->url?>" type="video/mp4">
      </video>
    </div>
    <div class="tab-bar master">
      <ul class="plot-tabs">
        <li><a class="tab-button active" data-tab="views">Views</a></li>
        <li><a class="tab-button" data-tab="reactions">Reactions</a></li>
        <li><a class="tab-button" data-tab="comments">Comments</a></li>
      </ul>
    </div>
    <div class="tab-bar sub-tabs views">
      <ul class="plot-tabs">
        <li><a class="tab-button active" data-tab="view_count">View Count</a></li>
      </ul>
    </div>
    <div class="tab-bar sub-tabs reactions hidden">
      <ul class="plot-tabs">
        <li><a class="tab-button active" data-tab="like">Like</a></li>
        <li><a class="tab-button" data-tab="wow">Wow</a></li>
        <li><a class="tab-button" data-tab="love">Love</a></li>
        <li><a class="tab-button" data-tab="haha">Haha</a></li>
        <li><a class="tab-button" data-tab="sad">Sad</a></li>
        <li><a class="tab-button" data-tab="angry">Angry</a></li>
      </ul>
    </div>
    <div class="tab-bar sub-tabs comments hidden">
      <ul class="plot-tabs">
        <li><a class="tab-button active" data-tab="comment_count">Comment Count</a></li>
        <li><a class="tab-button" data-tab="positive">Positive</a></li>
        <li><a class="tab-button" data-tab="negative">Negative</a></li>
        <li><a class="tab-button" data-tab="neutral">Neutral</a></li>
        <li><a class="tab-button" data-tab="trend">Trend</a></li>
      </ul>
    </div>
    <div id="plot-video-view_count" class="views video-placeholder demo-placeholder"></div>
    <div id="plot-video-like" class="reactions video-placeholder demo-placeholder"></div>
    <div id="plot-video-wow" class="reactions video-placeholder demo-placeholder"></div>
    <div id="plot-video-love" class="reactions video-placeholder demo-placeholder"></div>
    <div id="plot-video-haha" class="reactions video-placeholder demo-placeholder"></div>
    <div id="plot-video-sad" class="reactions video-placeholder demo-placeholder"></div>
    <div id="plot-video-angry" class="reactions video-placeholder demo-placeholder"></div>
    <div id="plot-video-comment_count" class="comments video-placeholder demo-placeholder"></div>
    <div id="plot-video-positive" class="comments video-placeholder demo-placeholder"></div>
    <div id="plot-video-negative" class="comments video-placeholder demo-placeholder"></div>
    <div id="plot-video-neutral" class="comments video-placeholder demo-placeholder"></div>
    <div id="plot-video-trend" class="comments video-placeholder demo-placeholder"></div>
    <h2>Video Keywords</h2>
    <p>
      @foreach(json_decode($video->keywords,1) as $kw)
        <?=$kw["name"]?>
      @endforeach
    </p>
  </div>
</div>
</div>
<br>
@endsection

@section('post-scripts')
<script type="text/javascript">
var theVideoPlots = {
    "view_count": {},
    "like": {},
    "wow": {},
    "love": {},
    "haha": {},
    "sad": {},
    "angry": {},
    "comment_count": {},
    "positive": {},
    "negative": {},
    "neutral": {},
    "trend": {},
  };
$(document).ready(function() {
  $(".tab-bar.master .tab-button").click(function() {
    $(".tab-bar.master .tab-button").removeClass("active");
    $(this).addClass("active");
    $(".tab-bar.sub-tabs").addClass('hidden');
    $(".tab-bar.sub-tabs." + $(this).attr("data-tab")).removeClass("hidden");
    $(".video-placeholder").hide();
    elem = $(".tab-bar.sub-tabs." + $(this).attr("data-tab") + " .tab-button.active")[0]
    $("#plot-video-" + $(elem).attr('data-tab')).show();
    $(".tab-bar.master .tab-button").removeClass("active");
    $(this).addClass("active");
  });
  var processData = function() {
    var a = <?=$video->get_info_by_frames()?>;
    console.log(a);
    console.log("sdsd");
    var graphData = {
      "view_count": [],
      "like": [],
      "wow": [],
      "love": [],
      "haha": [],
      "sad": [],
      "angry": [],
      "comment_count": [],
      "positive": [],
      "negative": [],
      "neutral": [],
      "trend": [],
    };
    for(var i=0; i<a.length; i++) {
      graphData["view_count"].push([a[i]["frame"], a[i]["view_count"]]);
      graphData["like"].push([a[i]["frame"], a[i]["like"]]);
      graphData["wow"].push([a[i]["frame"], a[i]["wow"]]);
      graphData["love"].push([a[i]["frame"], a[i]["love"]]);
      graphData["haha"].push([a[i]["frame"], a[i]["haha"]]);
      graphData["sad"].push([a[i]["frame"], a[i]["sad"]]);
      graphData["angry"].push([a[i]["frame"], a[i]["angry"]]);
      graphData["comment_count"].push([a[i]["frame"], a[i]["comment_count"]]);
      graphData["positive"].push([a[i]["frame"], a[i]["positive"]]);
      graphData["negative"].push([a[i]["frame"], a[i]["negative"]]);
      graphData["neutral"].push([a[i]["frame"], a[i]["neutral"]]);
      graphData["trend"].push([a[i]["frame"], a[i]["trend"]]);
    }
    return graphData;
  },
  videoPlot = function(data) {
    graphData = processData();
    theVideo = $("#video");
    // Create plot after video meta is loaded
    theVideo.on('loadedmetadata', function(event) {
      var theVideo = this,
          duration = theVideo.duration,
          options = {
                     series: {
                         curvedLines: {active: true}
                     },
                     cursors: [
                       {
                         name: 'Player',
                         color: 'red',
                         mode: 'x',
                         showIntersections: false,
                         symbol: 'triangle',
                         showValuesRelativeToSeries: 0,
                         position: {
                           x: 0.0,
                           y: 0.5
                         },
                         snapToPlot: 0
                       }
                     ],
                     xaxis: {
                       min: 0,
                       max: duration
                     },
                     clickable: false,
                     hoverable: false,
                     grid: {
                     }
                  },
            doPlot = function(analytic, color, options, yaxis) {
              // The video tag
              var theVideo = $("#video");
              // Add the created plot for the analytic to the plots var, to
              // keep reference
              options["yaxis"] = yaxis;
              theVideoPlots[analytic] = $.plot($("#plot-video-" + analytic), [
                {
                  data: graphData[analytic],
                  lines: { show: true, lineWidth: 2},
                  curvedLines: {apply: true, tension: 0.5},
                  color: color
                },
                {
                  data: graphData[analytic],
                  color: '#f03b20',
                  points: {show: true},
                },
              ], options);
              $("#plot-video-" + analytic).bind("cursorupdates", function(event, cursordata) {
                if(theVideo.get(0).paused) {
                  theVideo.get(0).currentTime = cursordata[0].x;
                  // // key phrases
                  // var posFrame = pad(Math.floor(cursordata[0].x), 4);
                  // if(graphData["speech"][posFrame]) {
                  //   $("#keyphrases").text(graphData["speech"][posFrame]["kp"]);
                  // }
                }
              });
            };
      doPlot("view_count", "orange", options, {"min": 0});
      doPlot("haha", "orange", options, {"min": 0});
      doPlot("sad", "darkblue", options, {"min": 0});
      doPlot("like", "lightblue", options, {"min": 0});
      doPlot("angry", "red", options, {"min": 0});
      doPlot("love", "pink", options, {"min": 0});
      doPlot("comment_count", "purple", options, {"min": 0});
      doPlot("wow", "brown", options, {"min": 0});
      doPlot("neutral", "lightgray", options, {"min": 0, "max":100});
      doPlot("positive", "brightgreen", options, {"min": 0, "max":100});
      doPlot("negative", "darkred", options, {"min": 0, "max":100});
      doPlot("trend", "black", options, {"min":-1, "max":1});
      $(".video-placeholder").hide();
      // Set interval to track video cursor
      setInterval(function () {
        if(!theVideo.paused) {
          onTrackedVideoFrame(theVideo.currentTime, theVideo.duration);
        }
      }, 50);
    });
  };
  $(".sub-tabs .plot-tabs a").click(function(e) {
    $(".video-placeholder").hide();
    kek = $(".tab-bar.master .tab-button.active")[0]
    kek = $(kek).attr("data-tab");
    $(".sub-tabs." + kek + " .plot-tabs a").removeClass("active");
    $("#plot-video-" + $(this).attr("data-tab")).show();
    $(this).addClass("active");
  });
  videoPlot();
  setTimeout(function() {
    $("#plot-video-view_count").show();
  }, 500);
});
function onTrackedVideoFrame(currentTime, duration){
  for (var key in theVideoPlots) {
    // skip loop if the property is from prototype
    if (!theVideoPlots.hasOwnProperty(key)) continue;
      theVideoPlots[key].setCursor(theVideoPlots[key].getCursors()[0], {
        position: {
          x: currentTime,
          y: 0.5
        }
      });
      var posFrame = pad(Math.floor(currentTime), 4);
      theVideoPlots[key].draw();
    }
}
function pad (str, max) {
  str = str.toString();
  return str.length < max ? pad("0" + str, max) : str;
}
</script>
@endsection
