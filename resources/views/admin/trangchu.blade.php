@extends('admin/layouts/default')

{{-- Page title --}}
@section('title')
Josh Admin Template
@parent
@stop

{{-- page level styles --}}
@section('header_styles')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/datatables/css/dataTables.bootstrap4.css') }}" />
    <link rel="stylesheet" href="{{asset('vendors/datatables/css/buttons.bootstrap4.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('vendors/select2/css/select2-bootstrap.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/pages/tables.css') }}" />

@stop

{{-- Page content --}}
@section('content')

<section class="content-header">
    <!--section starts-->
    <h1>Quản Lý Hóa CHất</h1>
    <ol class="breadcrumb">
        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="livicon" data-name="home" data-size="14" data-loop="true"></i>
                Dashboard
            </a>
        </li>

    </ol>
</section>

<!-- Main content -->
<section class="content pr-3 pl-3">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-12 my-3">
            <!-- Stack charts strats here-->
            <div class="card ">
                <div class="card-header bg-primary text-white ">
                    <span>
                        <i class="livicon" data-name="barchart" data-size="16" data-loop="true" data-c="#fff"
                            data-hc="#fff"></i> Line Chart
                    </span>
                    <span class="float-right">
                        <i class="fa fa-chevron-up showhide clickable"></i>
                        <i class="fa fa-times removepanel clickable"></i>
                    </span>
                </div>
                <div class="card-body">
                    <div class="app">
                        {!! $line->container() !!}
                    </div>
                    <!-- End Of Main Application -->

                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-12 my-3">
            <!-- Stack charts strats here-->
            <div class="card ">
                <div class="card-header bg-primary text-white ">
                    <span>
                        <i class="livicon" data-name="barchart" data-size="16" data-loop="true" data-c="#fff"
                            data-hc="#fff"></i> Line Chart
                    </span>
                    <span class="float-right">
                        <i class="fa fa-chevron-up showhide clickable"></i>
                        <i class="fa fa-times removepanel clickable"></i>
                    </span>
                </div>
                <div class="card-body">
                    <div class="app">
                        {!! $line->container() !!}
                    </div>
                    <!-- End Of Main Application -->
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-lg-12 my-3">
            <div class="card ">
                <div class="card-header bg-info text-white  clearfix">
                    <span>
                        <i class="livicon" data-name="list-ul" data-size="16" data-loop="true" data-c="#fff" data-hc="white"></i> Child rows
                        Show / Hide Columns
                    </span>
                    <span class="float-right ">
                        <i class="fa fa-chevron-up clickable"></i>
                    </span>

                </div>
                <div class="card-body table-responsive-lg table-responsive-md table-responsive-sm">
                    <strong>
                        Toggle column:
                    </strong>
                    <div class="btn-group" style="margin:10px 0;">
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="0">Name</button>
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="1">User Name
                        </button>
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="2">Email</button>
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="3">Department
                        </button>
                        <button type="button" class="toggle-vis btn btn-secondary" data-column="4">Contact
                        </button>
                    </div>
                    <table class="table table-striped table-bordered width100" id="example">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Contact</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Sarah Leannon</td>
                            <td>Sarah22</td>
                            <td>Sarah.Leannon13@hotmail.com</td>
                            <td>Toys</td>
                            <td>269-277-7340</td>
                        </tr>
                        <tr>
                            <td>Prudence Braun</td>
                            <td>Prudence.Braun</td>
                            <td>Prudence_Braun99@yahoo.com</td>
                            <td>Home</td>
                            <td>259-294-8730</td>
                        </tr>
                        <tr>
                            <td>Frederik Beier</td>
                            <td>Frederik_Beier71</td>
                            <td>Frederik_Beier88@yahoo.com</td>
                            <td>Health</td>
                            <td>115-781-3501</td>
                        </tr>
                        <tr>
                            <td>Devyn Heathcote</td>
                            <td>Devyn.Heathcote</td>
                            <td>Devyn.Heathcote@yahoo.com</td>
                            <td>Garden</td>
                            <td>030-811-3564</td>
                        </tr>
                        <tr>
                            <td>Mellie Kuhic</td>
                            <td>Mellie83</td>
                            <td>Mellie_Kuhic73@yahoo.com</td>
                            <td>Clothing</td>
                            <td>341-136-6740</td>
                        </tr>
                        <tr>
                            <td>Nova Sauer</td>
                            <td>Nova33</td>
                            <td>Nova_Sauer@hotmail.com</td>
                            <td>Music</td>
                            <td>243-061-3771</td>
                        </tr>
                        <tr>
                            <td>Demetrius Mills</td>
                            <td>Demetrius33</td>
                            <td>Demetrius.Mills@gmail.com</td>
                            <td>Kids</td>
                            <td>254-829-7615</td>
                        </tr>
                        <tr>
                            <td>Hope Hessel</td>
                            <td>Hope25</td>
                            <td>Hope_Hessel@gmail.com</td>
                            <td>Books</td>
                            <td>767-138-4997</td>
                        </tr>
                        <tr>
                            <td>Jackeline Champlin</td>
                            <td>Jackeline.Champlin</td>
                            <td>Jackeline.Champlin58@yahoo.com</td>
                            <td>Jewelery</td>
                            <td>059-603-4311</td>
                        </tr>
                        <tr>
                            <td>Meaghan Renner</td>
                            <td>Meaghan_Renner</td>
                            <td>Meaghan20@gmail.com</td>
                            <td>Automotive</td>
                            <td>939-379-9525</td>
                        </tr>
                        <tr>
                            <td>Peggie Kassulke</td>
                            <td>Peggie_Kassulke</td>
                            <td>Peggie_Kassulke@hotmail.com</td>
                            <td>Clothing</td>
                            <td>039-431-8024</td>
                        </tr>
                        <tr>
                            <td>Paige Walsh</td>
                            <td>Paige_Walsh</td>
                            <td>Paige.Walsh@gmail.com</td>
                            <td>Health</td>
                            <td>129-485-9542</td>
                        </tr>
                        <tr>
                            <td>Lamont Hettinger</td>
                            <td>Lamont.Hettinger77</td>
                            <td>Lamont75@yahoo.com</td>
                            <td>Automotive</td>
                            <td>056-955-2547</td>
                        </tr>
                        <tr>
                            <td>Giovanni Mosciski</td>
                            <td>Giovanni.Mosciski77</td>
                            <td>Giovanni14@yahoo.com</td>
                            <td>Music</td>
                            <td>013-913-2683</td>
                        </tr>
                        <tr>
                            <td>Gregoria Baumbach</td>
                            <td>Gregoria21</td>
                            <td>Gregoria_Baumbach@gmail.com</td>
                            <td>Shoes</td>
                            <td>613-910-1426</td>
                        </tr>
                        <tr>
                            <td>Orval Howe</td>
                            <td>Orval84</td>
                            <td>Orval_Howe64@hotmail.com</td>
                            <td>Grocery</td>
                            <td>679-519-4414</td>
                        </tr>
                        <tr>
                            <td>Angelica Conroy</td>
                            <td>Angelica_Conroy</td>
                            <td>Angelica.Conroy44@gmail.com</td>
                            <td>Industrial</td>
                            <td>360-505-8432</td>
                        </tr>
                        <tr>
                            <td>Gillian Hickle</td>
                            <td>Gillian.Hickle30</td>
                            <td>Gillian57@gmail.com</td>
                            <td>Home</td>
                            <td>159-164-6997</td>
                        </tr>
                        <tr>
                            <td>Willa Feeney</td>
                            <td>Willa58</td>
                            <td>Willa.Feeney17@hotmail.com</td>
                            <td>Games</td>
                            <td>939-683-3718</td>
                        </tr>
                        <tr>
                            <td>Elyse Cassin</td>
                            <td>Elyse.Cassin</td>
                            <td>Elyse.Cassin72@hotmail.com</td>
                            <td>Outdoors</td>
                            <td>584-891-5946</td>
                        </tr>
                        <tr>
                            <td>Miracle Hessel</td>
                            <td>Miracle.Hessel</td>
                            <td>Miracle.Hessel@gmail.com</td>
                            <td>Shoes</td>
                            <td>549-158-1206</td>
                        </tr>
                        <tr>
                            <td>Ethyl Pfannerstill</td>
                            <td>Ethyl_Pfannerstill</td>
                            <td>Ethyl_Pfannerstill@gmail.com</td>
                            <td>Shoes</td>
                            <td>643-622-2951</td>
                        </tr>
                        <tr>
                            <td>Adah Ortiz</td>
                            <td>Adah95</td>
                            <td>Adah48@gmail.com</td>
                            <td>Tools</td>
                            <td>270-691-3304</td>
                        </tr>
                        <tr>
                            <td>Berniece Klein</td>
                            <td>Berniece_Klein</td>
                            <td>Berniece_Klein63@yahoo.com</td>
                            <td>Health</td>
                            <td>211-699-9576</td>
                        </tr>
                        <tr>
                            <td>Jordi Breitenberg</td>
                            <td>Jordi59</td>
                            <td>Jordi.Breitenberg@yahoo.com</td>
                            <td>Sports</td>
                            <td>694-292-9691</td>
                        </tr>
                        <tr>
                            <td>Adalberto Satterfield</td>
                            <td>Adalberto94</td>
                            <td>Adalberto77@yahoo.com</td>
                            <td>Books</td>
                            <td>095-203-5357</td>
                        </tr>
                        <tr>
                            <td>Reese Turner</td>
                            <td>Reese21</td>
                            <td>Reese27@hotmail.com</td>
                            <td>Tools</td>
                            <td>225-264-1503</td>
                        </tr>
                        <tr>
                            <td>Tad Maggio</td>
                            <td>Tad17</td>
                            <td>Tad41@hotmail.com</td>
                            <td>Baby</td>
                            <td>238-050-3173</td>
                        </tr>
                        <tr>
                            <td>Arlo Smitham</td>
                            <td>Arlo_Smitham</td>
                            <td>Arlo_Smitham@hotmail.com</td>
                            <td>Movies</td>
                            <td>797-948-0375</td>
                        </tr>
                        <tr>
                            <td>Orlando Pfannerstill</td>
                            <td>Orlando20</td>
                            <td>Orlando17@yahoo.com</td>
                            <td>Sports</td>
                            <td>683-899-2503</td>
                        </tr>
                        <tr>
                            <td>Susan Hessel</td>
                            <td>Susan_Hessel88</td>
                            <td>Susan.Hessel60@gmail.com</td>
                            <td>Home</td>
                            <td>004-134-6430</td>
                        </tr>
                        <tr>
                            <td>Luigi Veum</td>
                            <td>Luigi_Veum</td>
                            <td>Luigi75@hotmail.com</td>
                            <td>Toys</td>
                            <td>846-211-9311</td>
                        </tr>
                        <tr>
                            <td>Emmie Rau</td>
                            <td>Emmie_Rau7</td>
                            <td>Emmie_Rau@hotmail.com</td>
                            <td>Games</td>
                            <td>151-075-1800</td>
                        </tr>
                        <tr>
                            <td>Jessika Johns</td>
                            <td>Jessika66</td>
                            <td>Jessika_Johns19@yahoo.com</td>
                            <td>Beauty</td>
                            <td>850-092-7783</td>
                        </tr>
                        <tr>
                            <td>Elmore Hartmann</td>
                            <td>Elmore_Hartmann</td>
                            <td>Elmore_Hartmann@gmail.com</td>
                            <td>Computers</td>
                            <td>821-435-4775</td>
                        </tr>
                        <tr>
                            <td>Liliana Schowalter</td>
                            <td>Liliana67</td>
                            <td>Liliana56@gmail.com</td>
                            <td>Tools</td>
                            <td>615-363-6678</td>
                        </tr>
                        <tr>
                            <td>Elmira Zboncak</td>
                            <td>Elmira_Zboncak</td>
                            <td>Elmira_Zboncak45@yahoo.com</td>
                            <td>Music</td>
                            <td>313-074-4827</td>
                        </tr>
                        <tr>
                            <td>Domenic Larkin</td>
                            <td>Domenic_Larkin32</td>
                            <td>Domenic_Larkin@gmail.com</td>
                            <td>Computers</td>
                            <td>463-196-1446</td>
                        </tr>
                        <tr>
                            <td>Josh Wolff</td>
                            <td>Josh_Wolff</td>
                            <td>Josh_Wolff@yahoo.com</td>
                            <td>Automotive</td>
                            <td>302-250-6870</td>
                        </tr>
                        <tr>
                            <td>Cullen Rosenbaum</td>
                            <td>Cullen_Rosenbaum</td>
                            <td>Cullen.Rosenbaum6@yahoo.com</td>
                            <td>Grocery</td>
                            <td>928-465-2210</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    


</section>
<!-- content -->
@stop

{{-- page level scripts --}}
@section('footer_scripts')
<script src="{{ asset('vendors/frappe/frappe-charts.min.iife.js') }}"></script>
<script src="{{ asset('vendors/highcharts/highcharts.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/jquery.dataTables.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.bootstrap4.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('vendors/datatables/js/dataTables.responsive.js') }}" ></script>
    <!--<script type="text/javascript" src="{{asset('vendors/datatables/js/buttons.bootstrap4.js')}}"></script>-->
    <script type="text/javascript" src="{{ asset('vendors/select2/js/select2.js') }}" ></script>
    <script type="text/javascript" src="{{ asset('js/pages/table-advanced2.js') }}" ></script>

{!! $line->script() !!}

@stop
