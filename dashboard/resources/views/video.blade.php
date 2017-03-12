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
  <span class="count_top"><i class="fa fa-user"></i> Max Attentive People</span>
  <div class="count" id="max-attentive">123</div>
</div>
<div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-user"></i> Average Happiness Index</span>
  <div class="count" id="avg-happiness-index">123</div>
</div>
<div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-clock-o"></i> Average Attention Index</span>
  <div class="count" id="avg-attention-index">0.43</div>
</div>
<div class="col-md-3 col-sm-4 col-xs-6 tile_stats_count">
  <span class="count_top"><i class="fa fa-user"></i> Most common sentiment</span>
  <div class="count green" id="common-sentiment">Happiness</div>
  <span class="count_bottom" id="sentiment-percentage"><i class="green">86% </i> Average</span>
</div>
</div>
<!-- /top tiles -->
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="dashboard_graph">
    <div class="col-md-9 col-sm-9 col-xs-12">
      <div id="plot-attention" class="demo-placeholder"></div>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
      <div class="x_title">
        <h2>Attention</h2>
        <div class="clearfix"></div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-6">
        <div>
          <p>Average Attention</p>
          <div class="">
            <div class="progress progress_sm">
              <div id="attention-bar-avg" class="progress-bar attention" role="progressbar"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
</div>
<br>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="dashboard_graph">
    <div class="col-md-9 col-sm-9 col-xs-12">
      <div id="plot-chart" class="demo-placeholder"></div>
    </div>
    <div class="col-md-3 col-sm-3 col-xs-12 bg-white">
      <div class="x_title">
        <h2>Average Sentiments</h2>
        <div class="clearfix"></div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-6">
        <div>
          <p>Happiness <span class="happiness-percent"></span></p>
          <div class="">
            <div class="progress progress_sm">
              <div id="happiness-bar" class="progress-bar happiness" role="progressbar"  ></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-6">
        <div>
          <p>Neutral <span class="neutral-percent"></span></p>
          <div class="">
            <div class="progress progress_sm">
              <div id="neutral-bar" class="progress-bar neutral" role="progressbar"  ></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-6">
        <div>
          <p>Contempt <span class="contempt-percent"></span></p>
          <div class="">
            <div class="progress progress_sm">
              <div id="contempt-bar" class="progress-bar contempt" role="progressbar"  ></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-6">
        <div>
          <p>Disgust <span class="disgust-percent"></span></p>
          <div class="">
            <div class="progress progress_sm">
              <div id="disgust-bar" class="progress-bar disgust" role="progressbar"  ></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-6">
        <div>
          <p>Anger <span class="anger-percent"></span></p>
          <div class="">
            <div class="progress progress_sm">
              <div id="anger-bar" class="progress-bar anger" role="progressbar"  ></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-6">
        <div>
          <p>Surprise <span class="surprise-percent"></span></p>
          <div class="">
            <div class="progress progress_sm">
              <div id="surprise-bar" class="progress-bar surprise" role="progressbar"  ></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-6">
        <div>
          <p>Fear <span class="fear-percent"></span></p>
          <div class="">
            <div class="progress progress_sm">
              <div id="fear-bar" class="progress-bar fear" role="progressbar"  ></div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-12 col-sm-12 col-xs-6">
        <div>
          <p>Sadness <span class="sadness-percent"></span></p>
          <div class="">
            <div class="progress progress_sm">
              <div id="sadness-bar" class="progress-bar sadness" role="progressbar"  ></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
  </div>
</div>
</div>
<br>
<div class="row">
<div class="col-md-12 col-sm-12 col-xs-12">
  <div class="dashboard_graph">
    <div class="video-container">
      <video
          id="video"
          class="video-active"
          width="100%"
          style="display:block;width:100%"
          controls="controls">
          <source src="<?=$video->url?>" type="video/mp4">
      </video>
    </div>
    <div class="tab-bar">
      <ul class="plot-tabs">
        <li><a class="tab-button active" data-tab="attention">Attention</a></li>
        <li><a class="tab-button" data-tab="happiness">Happiness</a></li>
        <li><a class="tab-button" data-tab="sadness">Sadness</a></li>
        <li><a class="tab-button" data-tab="anger">Anger</a></li>
        <li><a class="tab-button" data-tab="surprise">Surprise</a></li>
        <li><a class="tab-button" data-tab="fear">Fear</a></li>
        <li><a class="tab-button" data-tab="contempt">Contempt</a></li>
        <li><a class="tab-button" data-tab="disgust">Disgust</a></li>
        <li><a class="tab-button" data-tab="neutral">Neutral</a></li>
      </ul>
    </div>
    <div id="plot-video-attention" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-happiness" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-sadness" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-anger" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-surprise" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-fear" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-contempt" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-disgust" class="video-placeholder demo-placeholder"></div>
    <div id="plot-video-neutral" class="video-placeholder demo-placeholder"></div>
    <br>
    <div class="x_title">
      <h2>Key Phrases</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
      <p id="keyphrases">ugi pula in cur</p>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
</div>
<br>
@endsection

@section('post-scripts')

@endsection
