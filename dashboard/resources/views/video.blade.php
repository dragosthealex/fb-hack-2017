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
        <li><a class="tab-button active">View Count</a></li>
      </ul>
    </div>
    <div class="tab-bar sub-tabs reactions hidden">
      <ul class="plot-tabs">
        <li><a class="tab-button active">Like</a></li>
        <li><a class="tab-button">Wow</a></li>
        <li><a class="tab-button">Love</a></li>
        <li><a class="tab-button">Haha</a></li>
        <li><a class="tab-button">Sad</a></li>
        <li><a class="tab-button">Angry</a></li>
      </ul>
    </div>
    <div class="tab-bar sub-tabs comments hidden">
      <ul class="plot-tabs">
        <li><a class="tab-button active">Comment Count</a></li>
        <li><a class="tab-button">Positive</a></li>
        <li><a class="tab-button">Negative</a></li>
        <li><a class="tab-button">Neutral</a></li>
        <li><a class="tab-button">Trend</a></li>
      </ul>
    </div>
    <div id="plot-video-view_count" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-like" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-wow" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-love" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-haha" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-sad" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-angry" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-comment_count" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-positive" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-negative" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-neutral" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-trend" class="video-placeholder demo-placeholder"></div>
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
    $(".tab-bar.sub-tabs").addClass('hidden');
    $(".tab-bar.sub-tabs." + $(this).attr("data-tab")).removeClass("hidden");
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
  };

});
</script>
@endsection
