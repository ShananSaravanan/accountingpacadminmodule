@extends('Pages.mainlayout')


@section('maincontent')
<style>
    .content-wrapper,
    .main-header {
        width: 100%;
        margin: 0 auto;
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%; /* Adjust the width as needed */
        max-width: 600px; /* Set a maximum width */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }


</style>
<div class="row">
    <div class="col-md-12">
        <div class="card  mb-3 mt-3">
    <div class="card-header">
                
                <h5 class="card-title">@yield('pagename')</h5>
            </div>
        <div class="card-body">
            @yield('updatecontent')
            <button type="button" class="btn btn-success" id="newBtn" onclick="@yield('addBoxType')">@yield('buttonText')</button>
            <div id="myModal" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeAddBox()">&times;</span>
                    <form id="formArea" method="POST" action="" enctype="multipart/form-data">

                        <div id="add-box">
                            @yield('addBoxContent')
                            
                        </div>
                    </form>
                </div>
            </div>

        </div>
        </div>
    </div>
</div>
@endsection
