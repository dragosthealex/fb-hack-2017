@extends('layouts.app')

@section('title', config('app.name') . ' | Dashboard')
@section('css')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.13/css/jquery.dataTables.min.css">
<style type="text/css">
.padded {
    padding: 40px 0;
}
.dash-title {
    margin: 30px 30px 0;
}
</style>
@endsection

@section('content')
<div class="row">
    <h1 class="dash-title">Dashboard</h1>
    <div class="col-md-10 col-md-offset-1 padded">
        <a href="{{ url('/hooks/live-video/test') }}" onclick="alert('go Live on Facebook!')" class="btn btn-large btn-primary">Start Live</a>
        <hr>
        <h3>Videos</h3>
        <table class="table-striped table-responsive datatables">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Total Reactions</th>
                    <th>Total Views</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach(Auth::user()->videos as $video)
                <tr>
                    <td><?=$video->id?></td>
                    <td><?=$video->description?$video->description:'N/A'?></td>                    
                    <td><?=$video->created_at?></td>                    
                    <td><?=$video->get_total_reactions()?></td>                    
                    <td><?=$video->get_total_views()?></td>
                    <td><a class="btn btn-default btn-success" href="{{ url('/videos/' . $video->id) }}">View</a>               
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('post-scripts')
<script type="text/javascript" src="//cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('.datatables').DataTable();
});     
</script>
@endsection