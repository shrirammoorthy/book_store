<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel - Activity List Application</title>
    {{-- CSS --}}
    <link href="{{ asset('css/jquery-ui.css') }}" rel="stylesheet">
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" />-->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    {{-- Font awesome --}}
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a class= "btn btn-danger" href="{{ route('register') }}">Register</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ url('/users') }}">All Users Book List</a>
                                        <a href="{{ url('/search') }}">Global Books Search</a>
                                        <a href="{{ url('/list') }}">My Book List</a>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-lg-offset-3 col-lg-6">
				<div class="panel panel-default">
				  <div class="panel-body" id="items">
				    @if(count($items_array)>0)
                        <ul class="list-group">
                        <?php 
                            //  echo "<pre>";print_r($items_array);
                            //  foreach($items_array as $i => $item_val){
                            //  echo $item_val['link'];
                            //  }
                        ?>
                        <table id="myTable" class="display">
                            <thead>
                                <tr>
                                    <th>Book</th>
                                    <th>Title</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items_array as $ikey => $item)
                                    <tr>
                                        <td><img src="{{$item['image']}}"></td>
                                        <td><a href="{{$item['link']}}">{{$item['title']}}</a></td>
                                        <td>
                                        @if($item['status'] == 0)
                                        <input type="hidden" class="title" value= "{{$item['title']}}"><input type="hidden" class="image_url" value= "{{$item['image']}}"><input type="hidden" class="link_url" value= "{{$item['link']}}"><button class="add btn btn-success">Add to List</button>
                                        @else
                                        <button class="btn btn-success" disabled="disabled">Added to List</button>
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Search result is empty.</p>
                    @endif  
				  </div>
				</div>
			</div>
    {{ csrf_field() }}
    {{-- script jquery --}}
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
    {{-- script bootstrap --}}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>   
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>    
    {{-- My script --}}
    <script>
        $(document).ready(function(){
            $('#myTable').DataTable();
            $('.add').click(function(){
                $(this).text('Added to List').attr("disabled", "disabled");
                var text = $(this).closest('td').find('.title').val();
                var img = $(this).closest('td').find('.image_url').val();
                var link = $(this).closest('td').find('.link_url').val();
                         $.post('create', {'text': text,'img': img,'link': link,'_token':$('input[name=_token]').val()}, function(data) {
                        $('#items').load(location.href + ' #items');
                        //window.location.reload();
                    });
            });     
        });
    </script>
</body>
</html>
