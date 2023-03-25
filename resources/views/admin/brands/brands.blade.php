@extends('admin.layout.layout')
@section('content')

<div class="main-panel">
   <div class="content-wrapper">
      <div class="row">
         <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
               <div class="card-body">
                  <h4 class="card-title">Brands</h4>
                  <!-- <p class="card-description">
                     Add class <code>.table-bordered</code>
                  </p> -->
                  <a style="max-width: 130px; float: right; display: inline-block;" href="{{ url('admin/add-edit-brand') }}" class="btn btn-block btn-primary">Add brand</a>
                  @if(Session::has('success_message'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                     <strong>Success: </strong> {{ Session::get('success_message')}}
                     <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  </div>
                  @endif
                  <div class="table-responsive pt-3">
                     <table id="brands" class="table table-bordered">
                        <thead>
                           <tr>
                              <th>
                                ID
                              </th>
                              <th>
                                Name
                              </th>
                              <th>
                                Status
                              </th>
                              <th>
                                Actions
                              </th>
                           </tr>
                        </thead>
                        <tbody>
                            @foreach($brands as $brand)
                           <tr>
                              <td>
                                {{ $brand['id'] }}
                              </td>
                              <td>
                                {{ $brand['name'] }}
                              </td>
                              <td>
                                @if($brand['status']==1)
                                  <a class="updateBrandStatus" id="brand-{{ $brand['id'] }}" brand_id="{{ $brand['id'] }}" href="javascript:void(0)"><i style="font-size:25px;" class="mdi mdi-bookmark-check" status="Active"></i></a>
                                @else
                                  <a class="updateBrandStatus" id="brand-{{ $brand['id'] }}" brand_id="{{ $brand['id'] }}" href="javascript:void(0)"><i style="font-size:25px;" class="mdi mdi-bookmark-outline" status="Inactive"></i></a>
                                @endif
                              </td>
                              <td>
                                <a href="{{ url('admin/add-edit-brand/'.$brand['id']) }}"><i style="font-size:25px;" class="mdi mdi-lead-pencil"></i></a>
                                <!-- <a title="brand" class="confirmDelete" href="{{ url('admin/delete-brand/'.$brand['id']) }}"><i style="font-size:25px;" class="mdi mdi-delete-forever"></i></a> -->
                                <a href="javascript:void(0)" module="brand" moduleId={{ $brand['id'] }} class="confirmDelete"><i style="font-size:25px;" class="mdi mdi-delete-forever"></i></a>
                              </td>
                           </tr>
                           @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!-- content-wrapper ends -->
   <!-- partial:../../partials/_footer.html -->
   @include('admin.layout.footer')
   <!-- partial -->
</div>
<!-- main-panel ends -->
@endsection