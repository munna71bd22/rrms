@extends('layouts.app')

@section('content')

    <style>

        .canvas-container {
            margin-right: auto;
            margin-left: auto;
        }
    </style>

    <div class="container-fluid text-center">
        <p class="text-muted">Choose where ever you want to sit in a restaurant</p>
        <div class="form-group admin-menu">

            <div class="btn-group">

                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#backDropModal"> &#9547; Floor
                </button>
                <select onchange="setFloorId(this)" id="floor" name="floor" class="btn btn-secondary">
                    <option selected disabled>Select Floor</option>
                    @foreach($floors as $floor)
                        <option value="{{$floor->id}}">{{$floor->title}}</option>
                    @endforeach
                </select>

                <select onchange="addTable(this)" id="tbl" class="btn btn-primary">
                    <option selected disabled>+ &#9711; Table</option>
                    <option value="1">1 Seat Table</option>
                    <option value="2">2 Seats Table</option>
                    <option value="3">3 Seats Table</option>
                    <option value="4">4 Seats Table</option>
                    <option value="6">6 Seats Table</option>
                    <option value="8">8 Seats Table</option>
                    <option value="10">10 Seats Table</option>
                    <option value="12">12 Seats Table</option>
                    <option value="16">16 Seats Table</option>
                </select>

                <button class="btn btn-danger remove">Remove</button>
                <button onclick="buildSeat()" class="btn btn-success remove">Save</button>
                <!--
                <button class="btn btn-danger customer-mode">Customer Mode</button>
                <button class="btn btn-danger admin-mode">Admin Mode</button>
                -->

            </div>
        </div>

        <canvas id="canvas" width="812" height="812"></canvas>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="backDropModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{route('seat-builder.store')}}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="backDropModalTitle">New Floor</h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col mb-3">
                            <label for="nameBackdrop" class="form-label">Name</label>
                            <input
                                type="text"
                                id="nameBackdrop"
                                class="form-control"
                                placeholder="Enter Name"
                                name="title"
                                required
                            />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
    <!--/ Bootstrap modals -->
    <!-- partial -->
    <script src="{{asset('assets/seat-builder/fabric.min.js')}}"></script>
    <script>
        let buildData = {
            floor_id: null,
            items: []
        };
        let canvas


        let canvasEl = document.getElementById('canvas')


        canvas = new fabric.Canvas('canvas')
        /*
        fabric.Image.fromURL("{{asset('assets/seat-builder/')}}", function(img) {
            // add background image
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                //scaleX: canvas.width / img.width,
                scaleY: canvas.height / img.height/2.5
            });
        });
        */


        document.querySelectorAll('.remove')[0].addEventListener('click', function () {
            const o = canvas.getActiveObject()
            if (o) {
                o.remove()
                canvas.remove(o)
                canvas.discardActiveObject()
                canvas.renderAll()
            }
        })

        /*

        document.querySelectorAll('.customer-mode')[0].addEventListener('click', function () {
            canvas.getObjects().map(o => {
                o.hasControls = false
                o.lockMovementX = true
                o.lockMovementY = true
                if (o.type === 'chair' || o.type === 'bar' || o.type === 'wall') {
                    o.selectable = false
                }
                o.borderColor = '#38A62E'
                o.borderScaleFactor = 2.5
            })
            canvas.selection = false
            canvas.hoverCursor = 'pointer'
            canvas.discardActiveObject()
            canvas.renderAll()
            document.querySelectorAll('.admin-menu')[0].style.display = 'none'
            document.querySelectorAll('.customer-menu')[0].style.display = 'block'
        })

        document.querySelectorAll('.admin-mode')[0].addEventListener('click', function () {
            canvas.getObjects().map(o => {
                o.hasControls = true
                o.lockMovementX = false
                o.lockMovementY = false
                if (o.type === 'chair' || o.type === 'bar' || o.type === 'wall') {
                    o.selectable = true
                }
                o.borderColor = 'rgba(102, 153, 255, 0.75)'
                o.borderScaleFactor = 1
            })
            canvas.selection = true
            canvas.hoverCursor = 'move'
            canvas.discardActiveObject()
            canvas.renderAll()
            document.querySelectorAll('.admin-menu')[0].style.display = 'block'
            document.querySelectorAll('.customer-menu')[0].style.display = 'none'
        })

        function addDefaultObjects() {
        }

        addDefaultObjects();

         */

        function setFloorId(t) {
            buildData.floor_id = t.value;
            getBuildData();
        }


        function buildSeat() {
            let selectedFloor = $('#floor').val();
            if (selectedFloor < 1) {
                alert('please select floor first.Then try to save.')
            } else {
                $.ajax({
                    url: '{{route('seat-builder.build')}}',
                    type: 'POST',
                    headers: {'X-CSRF-TOKEN': '{{@csrf_token()}}'},
                    data: buildData,
                    success: function (res) {
                        alert(res.message)
                    },
                    error: function (res) {
                        console.log(res)
                    }
                })
            }
        }

        function getBuildData() {
            let floor_id = $('#floor').val();

            $.ajax({
                url: '{{route('seat-builder.get-build-data')}}',
                type: 'GET',
                data: {floor_id: floor_id},
                success: function (res) {
                    res.data.map(function (obj, key) {
                        let item = JSON.parse(obj.canvas_obj);
                        canvas.add(item);
                        console.log(obj)
                    })
                },
                error: function (res) {
                    console.log(res)
                }
            })
        }


        function addTable(t) {
            let tblImgUrl = "../assets/seat-builder/tbl" + t.value + ".png";

            fabric.Image.fromURL(tblImgUrl, function (img) {
                var oImg = img.set({left: 0, top: 0}).scale(.75);
                let text = new fabric.Text("Table with " + t.value + ' Seat', {
                    left: 10,
                    top: 150,
                    fontSize: 15,
                    fill: 'black'
                });
                let title = new fabric.Text(prompt("Please enter table name/code:", ""), {
                    left: 10,
                    top: 170,
                    fontSize: 15,
                    fill: 'black'
                });
                let group = new fabric.Group([oImg, text, title],{
                    id: generateId(),
                    title: title
                })
                let obj = canvas.add(group);
            });

        }


        canvas.on('object:modified', function (e) {
            pushItemForStore(e)

        })

        canvas.on('object:store', function (e) {
            pushItemForStore(e)
        })

        function pushItemForStore(e) {
            let scaledObject = e.target;
            buildData.items.push(scaledObject)
            console.log('Width =  ' + scaledObject.getWidth());
            console.log('Height = ' + scaledObject.getHeight());
        }

        function generateId() {
            return Math.random().toString(36).substr(2, 8)
        }

    </script>
@endsection
