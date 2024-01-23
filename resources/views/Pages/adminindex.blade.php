@extends('Pages.mainlayout')
@section('maincontent')
<script src="{{asset('dist/js/pages/dashboard.js')}}"></script>
<div class="row">

          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-maroon">
              <div class="inner">
                <h3>{{$activeSubCount}}</h3>

                <p>Active Subscriptions</p>
              </div>
              <div class="icon">
              <i class="fa-solid fa-credit-card" style="font-size: 72px;"></i>
              </div>
              <a href="{{route('toShowSubscription')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{$activeAssignments}}</h3>

                <p>On-going Assignments</p>
              </div>
              <div class="icon">
              <i class="fa-solid fa-file-contract" style="font-size: 72px;"></i>
              </div>
              <a href="{{route('toShowAssignment')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{$totalUsers}}</h3>

                <p>Total Users</p>
              </div>
              <div class="icon">
              <i class="fa-solid fa-users" style="font-size: 72px;"></i>
              </div>
              <a href="{{ route('displayUsers',['searchType' => 'allusers']) }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-purple">
              <div class="inner">
                <h3>RM {{$totalTransaction}}</h3>

                <p>Revenue Generated </p>
              </div>
              <div class="icon">
              <i class="fa-solid fa-money-bills" style="font-size: 72px;"></i>
              </div>
              <a href="{{route('toShowTransaction')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-gradient-secondary">
              <span class="info-box-icon"><i class="fa-solid fa-briefcase"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Businesses Registered</span>
                <span class="info-box-number">{{$totalbusinesses}}</span>

                
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-gradient-info">
              <span class="info-box-icon"><i class="fa-solid fa-building-columns"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Firms Engaged</span>
                <span class="info-box-number">{{$firmsengaged}}</span>

              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-gradient-warning">
              <span class="info-box-icon"><i class="fa-regular fa-flag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">States Covered</span>
                <span class="info-box-number">{{$statesCovered}}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-gradient-olive">
              <span class="info-box-icon"><i class="fa-solid fa-cubes"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Most Subscribed Package</span>
                <span class="info-box-number">{{$packageDetail}} ({{$packageCount}})</span>

              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- ./col -->
        </div>
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-7 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
            
            <div class="card-header bg-gradient-danger text-center">
              
              <h3>
              <i class="fa-solid fa-fire"></i>
              Popularity
              </h3>
              </div>
    <div class="card-body d-flex flex-column align-items-center">
    
        <!-- Row 1: Title -->

        <!-- Row 2: Select Dropdown -->
        <div class="mb-3" style="width: 100%;">
            <select id="mySelect"  class="form-control select2" style="width: 100%; text-align: center;" >
                <option selected="selected" value="package">Subscribed Packages</option>
                <option value="firm">Firms</option>
                <option value="honorificcode">User Honorifics</option>
                <option value="user">User Types</option>
                <option value="addresstype">Address Types</option>
                <option value="businesstype">Business Types</option>
                <option value="firmtype">Firm Types</option>
            </select>
        </div>

        <!-- Row 3: Pills -->
        <div class="card-tools">
        
            <ul class="nav nav-pills ml-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#bar-graph" data-toggle="tab">Bar</a>
                </li>
            </ul>
        </div>
    </div><!-- /.card-body -->

    

    <div class="tab-content p-0">
        <!-- Morris chart - Sales -->
        <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 300px;">
            <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
        </div>
        <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
            <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
        </div>
        <div class="chart tab-pane" id="bar-graph" style="position: relative; height: 300px;">
            <canvas id="bar-graph-canvas" height="300" style="height: 300px;"></canvas>
        </div>
    </div><!-- /.tab-content -->
</div>


            <!-- /.card -->
            <div class="card bg-black">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">
            <i class="fa-solid fa-database"></i>
            Records In Storage
        </h3>

        <select id="mySelect2" class="form-control mx-2" style="max-width: 70%;">
            @foreach($modelNames as $modelName)
              <option value="{{$modelName}}">{{$modelName}}</option>
            @endforeach
        </select>

        <div class="card-tools">
            <button type="button" class="btn bg-black btn-sm" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <canvas class="chart" id="line-chart2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
    </div>
    <!-- /.card-body -->
</div>


            
            <!-- /.card -->
</section>
          
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">

            <!-- solid sales graph -->
            <div class="card bg-gradient-info">
              <div class="card-header border-0">
                <h3 class="card-title">
                <i class="fa-solid fa-chart-line"></i>
                  Transaction Trends
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-transparent">
                <div class="row">
                <div class="col-4 text-center">
              <div id="highestAmount" class="text-white" style="font-size: 16px;"></div>
             <div class="text-white">Highest Revenue</div>
            </div>

                  <!-- ./col -->
                  <div class="col-4 text-center">
                  <div id="averageAmount" class="text-white" style="font-size: 20px;"></div>

                    <div class="text-white">Average Revenue</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                  <div id="lowestAmount" class="text-white" style="font-size: 16px;"></div>

                    <div class="text-white">Lowest Revenue</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->

            <!-- Calendar -->
            <div class="card bg-gradient-navy">
              <div class="card-header border-0">

                <h3 class="card-title">
                  <i class="far fa-calendar-alt"></i>
                  Calendar
                </h3>
                <!-- tools card -->
                <div class="card-tools">
                  <!-- button with a dropdown -->
                  
                  <button type="button" class="btn btn-secondary btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  
                </div>
                <!-- /. tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body pt-0">
                <!--The calendar -->
                <div id="calendar" style="width: 100%"></div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </section>
          <!-- right col -->
        </div>
@endsection