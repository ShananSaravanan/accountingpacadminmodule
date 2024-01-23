@extends('Pages.mainlayout')
@section('title', 'Admin | Recycle Bin')
@section('maincontent')
<style>
    .content-wrapper,
    .main-header {
        width: 100%;
        margin: 0 auto;
    }
</style>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-3 mt-3">
            <div class="card-header">
                <h5 class="card-title">Recycle Bin</h5>
            </div>
            <div class="card-body">
                
                @csrf
                @method('post')

                @if (count($softDeletedItems) > 0)
                    @foreach ($softDeletedItems as $modelName => $items)
                        @if (count($items) > 0)
                       
                            <h2>{{ class_basename($modelName) }}</h2>
                            <table id="{{ $modelName }}Table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        @foreach ($allAttributes[$modelName] as $attribute)
                                            <th>{{ $attribute }}</th>
                                        @endforeach
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            @foreach ($allAttributes[$modelName] as $attribute)
                                            
                                                <td>
                                                <label for="" hidden> {{ $item->$attribute ?? '' }}</label>
                                                <input class="form-control" type="text" value="{{ $item->$attribute ?? '' }}" readonly></td>
                                            @endforeach
                                            <td>
                                                <form action="{{ route('restoreDeletedItems', ['modelName' => $modelName, 'id' => $item->id]) }}" method="post">
                                                    @method('POST')
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">Restore</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                        @endif
                    @endforeach
                @else
                    <p>No soft deleted items found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize DataTable for all tables
    $(document).ready(function () {
        $('[id$="Table"]').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
        });
    });
</script>

@endsection
