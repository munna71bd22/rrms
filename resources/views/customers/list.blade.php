@extends('layouts.app')

@section('content')

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Bootstrap Table with Header - Light -->
        <div class="card">


            <div class="row">
                <div class="col">
                    <h5 class="card-header">Customer List</h5>
                </div>
                <div class="col">
                    <nav style="width:90%;height:50px;"
                         class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme">

                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search fs-4 lh-0"></i>
                                <input
                                    id="q"
                                    name="q"
                                    type="search"
                                    class="form-control border-0 shadow-none"
                                    placeholder="Search..."
                                    aria-label="Search..."
                                />
                            </div>
                        </div>
                        <!-- /Search -->
                    </nav>

                </div>
            </div>


            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Avatar</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody id="tdata" class="table-border-bottom-0">
                    <tr>
                        <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>Angular Project</strong></td>
                        <td>Albert Cook</td>
                        <td>
                            <ul class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                <li
                                    data-bs-toggle="tooltip"
                                    data-popup="tooltip-custom"
                                    data-bs-placement="top"
                                    class="avatar avatar-xs pull-up"
                                    title="Lilian Fuller"
                                >
                                    <img src="../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle"/>
                                </li>

                            </ul>
                        </td>

                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="javascript:void(0);"
                                    ><i class="bx bx-edit-alt me-1"></i> Edit</a
                                    >
                                    <a class="dropdown-item" href="javascript:void(0);"
                                    ><i class="bx bx-trash me-1"></i> Delete</a
                                    >
                                </div>
                            </div>
                        </td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Bootstrap Table with Header - Light -->
    @push('scripts')
        <script>
            let options = {
                page: 1,
                limit: 10,
                total: 2,
                q: $('#q').val()
            };

            function getListData() {
                $.ajax({
                    url: '{{route("customers.index")}}',
                    type: 'GET',
                    data: options,
                    success: function (res) {
                        let rows = '';

                        res.data.map((obj, k) => {
                            rows += `<tr>
                                    <td>${obj.name}</td>
                                   <td>${obj.email}</td>
                                   <td>
                                   <img height="30px" src="../assets/img/avatars/5.png" alt="Avatar" class="rounded-circle" />
                                   </td>

                                  <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            ><i class="bx bx-edit-alt me-1"></i> Edit</a
                                        >
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            ><i class="bx bx-trash me-1"></i> Delete</a
                                        >
                                        </div></td> </tr>`;
                        });

                        $('#tdata').html(rows)

                    },
                    error: function (err) {
                        console.log(err)
                    }
                })
            }


            getListData();

            $('#q').on('change', function (e) {
                options.q = this.value;
                getListData()
            })

        </script>
    @endpush

@endsection
