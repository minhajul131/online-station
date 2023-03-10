@extends('admin.layout.layout')
@section('content')

<div class="main-panel">
   <div class="content-wrapper">
      <div class="row">
         <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
               <div class="card-body">
                  <h4 class="card-title">{{ $title }}</h4>
                  <!-- <p class="card-description">
                     Add class <code>.table-bordered</code>
                  </p> -->
                  <div class="table-responsive pt-3">
                     <table class="table table-bordered">
                        <thead>
                           <tr>
                              <th>
                                ID
                              </th>
                              <th>
                                Name
                              </th>
                              <th>
                                Type
                              </th>
                              <th>
                                Mobile
                              </th>
                              <th>
                                Email
                              </th>
                              <th>
                                Image
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
                            @foreach($admins as $admin)
                           <tr>
                              <td>
                                {{ $admin['id'] }}
                              </td>
                              <td>
                                {{ $admin['name'] }}
                              </td>
                              <td>
                                {{ $admin['trpe'] }}
                              </td>
                              <td>
                                {{ $admin['mobile'] }}
                              </td>
                              <td>
                                {{ $admin['email'] }}
                              </td>
                              <td>
                                <img src="{{ asset('admin/images/photos/'.$admin['image']) }}" alt="">
                              </td>
                              <td>
                                @if($admin['status']==1)
                                    <i style="font-size:25px" class="mdi mdi-bookmark-check"></i>
                                @else
                                    <i style="font-size:25px" class="mdi mdi-bookmark-outline"></i>
                                @endif
                              </td>
                              <td>
                                @if($admin['trpe']=="vendor")
                                    <a href="{{ url('admin/view-vendor-details/'.$admin['id']) }}"><i style="font-size:25px" class="mdi mdi-file-document"></i></a>
                                @endif
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