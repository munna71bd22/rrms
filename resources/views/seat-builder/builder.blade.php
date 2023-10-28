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
                <select id="floor" name="floor" class="btn btn-secondary">
                    <option selected disabled>Select Floor</option>
                    @foreach($floors as $floor)
                        <option value="{{$floor->id}}">{{$floor->title}}</option>
                    @endforeach
                </select>


                <button class="btn btn-primary rectangle">+ &#9647; Table</button>
                <button class="btn btn-primary circle">+ &#9711; Table</button>
                <button class="btn btn-primary triangle">+ &#9651; Table</button>
                <button class="btn btn-primary chair">+ Chair</button>
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
            canvasData: [],
            items: []
        };
        let canvas
        let number
        const grid = 30
        const backgroundColor = '#f8f8f8'
        const lineStroke = '#ebebeb'
        const tableFill = 'rgba(150, 111, 51, 0.7)'
        const tableStroke = '#694d23'
        const tableShadow = 'rgba(0, 0, 0, 0.4) 3px 3px 7px'
        const chairFill = 'rgba(67, 42, 4, 0.7)'
        const chairStroke = '#32230b'
        const chairShadow = 'rgba(0, 0, 0, 0.4) 3px 3px 7px'
        const barFill = 'rgba(0, 93, 127, 0.7)'
        const barStroke = '#003e54'
        const barShadow = 'rgba(0, 0, 0, 0.4) 3px 3px 7px'
        const barText = 'Bar'
        const wallFill = 'rgba(136, 136, 136, 0.7)'
        const wallStroke = '#686868'
        const wallShadow = 'rgba(0, 0, 0, 0.4) 5px 5px 20px'

        let widthEl = 812
        let heightEl = 812
        let canvasEl = document.getElementById('canvas')

        function initCanvas() {
            if (canvas) {
                canvas.clear()
                canvas.dispose()
            }

            canvas = new fabric.Canvas('canvas')
            number = 1
            canvas.backgroundColor = backgroundColor

            for (let i = 0; i < (canvas.height / grid); i++) {
                const lineX = new fabric.Line([0, i * grid, canvas.height, i * grid], {
                    stroke: lineStroke,
                    selectable: false,
                    type: 'line'
                })
                const lineY = new fabric.Line([i * grid, 0, i * grid, canvas.height], {
                    stroke: lineStroke,
                    selectable: false,
                    type: 'line'
                })
                sendLinesToBack()
                canvas.add(lineX)
                canvas.add(lineY)
            }

            canvas.on('object:moving', function (e) {
                snapToGrid(e.target)
            })

            canvas.on('object:scaling', function (e) {
                if (e.target.scaleX > 5) {
                    e.target.scaleX = 5
                }
                if (e.target.scaleY > 5) {
                    e.target.scaleY = 5
                }
                if (!e.target.strokeWidthUnscaled && e.target.strokeWidth) {
                    e.target.strokeWidthUnscaled = e.target.strokeWidth
                }
                if (e.target.strokeWidthUnscaled) {
                    e.target.strokeWidth = e.target.strokeWidthUnscaled / e.target.scaleX
                    if (e.target.strokeWidth === e.target.strokeWidthUnscaled) {
                        e.target.strokeWidth = e.target.strokeWidthUnscaled / e.target.scaleY
                    }
                }
            })

            canvas.on('object:modified', function (e) {
                e.target.scaleX = e.target.scaleX >= 0.25 ? (Math.round(e.target.scaleX * 2) / 2) : 0.5
                e.target.scaleY = e.target.scaleY >= 0.25 ? (Math.round(e.target.scaleY * 2) / 2) : 0.5
                snapToGrid(e.target)
                if (e.target.type === 'table') {
                    canvas.bringToFront(e.target)
                } else {
                    canvas.sendToBack(e.target)
                }
                sendLinesToBack()
            })

            canvas.observe('object:moving', function (e) {
                checkBoudningBox(e)
            })
            canvas.observe('object:rotating', function (e) {
                checkBoudningBox(e)
            })
            canvas.observe('object:scaling', function (e) {
                checkBoudningBox(e)
            })
        }

        initCanvas()

        function resizeCanvas() {
            canvasEl.width = 812
            canvasEl.height = 812
            const canvasContainerEl = document.querySelectorAll('.canvas-container')[0]
            canvasContainerEl.style.width = canvasEl.width
            canvasContainerEl.style.height = canvasEl.height
        }

        resizeCanvas()


        function generateId() {
            return Math.random().toString(36).substr(2, 8)
        }

        function addRect(left, top, width, height) {
            const id = generateId()
            const o = new fabric.Rect({
                width: width,
                height: height,
                fill: tableFill,
                stroke: tableStroke,
                strokeWidth: 2,
                shadow: tableShadow,
                originX: 'center',
                originY: 'center',
                centeredRotation: true,
                snapAngle: 45,
                selectable: true
            })
            const t = new fabric.IText(number.toString(), {
                fontFamily: 'Calibri',
                fontSize: 14,
                fill: '#fff',
                textAlign: 'center',
                originX: 'center',
                originY: 'center'
            })
            const g = new fabric.Group([o, t], {
                left: left,
                top: top,
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'table',
                id: id,
                number: number
            })
            canvas.add(g)
            number++
            return g
        }

        function addCircle(left, top, radius) {
            const id = generateId()
            const o = new fabric.Circle({
                radius: radius,
                fill: tableFill,
                stroke: tableStroke,
                strokeWidth: 2,
                shadow: tableShadow,
                originX: 'center',
                originY: 'center',
                centeredRotation: true
            })
            const t = new fabric.IText(number.toString(), {
                fontFamily: 'Calibri',
                fontSize: 14,
                fill: '#fff',
                textAlign: 'center',
                originX: 'center',
                originY: 'center'
            })
            const g = new fabric.Group([o, t], {
                left: left,
                top: top,
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'table',
                id: id,
                number: number
            })
            canvas.add(g)
            number++
            return g
        }

        function addTriangle(left, top, radius) {
            const id = generateId()
            const o = new fabric.Triangle({
                radius: radius,
                fill: tableFill,
                stroke: tableStroke,
                strokeWidth: 2,
                shadow: tableShadow,
                originX: 'center',
                originY: 'center',
                centeredRotation: true
            })
            const t = new fabric.IText(number.toString(), {
                fontFamily: 'Calibri',
                fontSize: 14,
                fill: '#fff',
                textAlign: 'center',
                originX: 'center',
                originY: 'center'
            })
            const g = new fabric.Group([o, t], {
                left: left,
                top: top,
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'table',
                id: id,
                number: number
            })
            canvas.add(g)
            number++;
            return g
        }

        function addChair(left, top, width, height) {
            const o = new fabric.Rect({
                left: left,
                top: top,
                width: 30,
                height: 30,
                fill: chairFill,
                stroke: chairStroke,
                strokeWidth: 2,
                shadow: chairShadow,
                originX: 'left',
                originY: 'top',
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'chair',
                id: generateId()
            })
            canvas.add(o)
            return o
        }

        function addBar(left, top, width, height) {
            const o = new fabric.Rect({
                width: width,
                height: height,
                fill: barFill,
                stroke: barStroke,
                strokeWidth: 2,
                shadow: barShadow,
                originX: 'center',
                originY: 'center',
                type: 'bar',
                id: generateId()
            })
            const t = new fabric.IText(barText, {
                fontFamily: 'Calibri',
                fontSize: 14,
                fill: '#fff',
                textAlign: 'center',
                originX: 'center',
                originY: 'center'
            })
            const g = new fabric.Group([o, t], {
                left: left,
                top: top,
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'bar'
            })
            canvas.add(g)
            return g
        }

        function addWall(left, top, width, height) {
            const o = new fabric.Rect({
                left: left,
                top: top,
                width: width,
                height: height,
                fill: wallFill,
                stroke: wallStroke,
                strokeWidth: 2,
                shadow: wallShadow,
                originX: 'left',
                originY: 'top',
                centeredRotation: true,
                snapAngle: 45,
                selectable: true,
                type: 'wall',
                id: generateId()
            })
            canvas.add(o)
            return o
        }

        function snapToGrid(target) {
            buildData.items.push({id:target.id,no:target.number,type:target.type,top:target.top})
            target.set({
                left: Math.round(target.left / (grid / 2)) * grid / 2,
                top: Math.round(target.top / (grid / 2)) * grid / 2
            })
        }

        function checkBoudningBox(e) {
            const obj = e.target

            if (!obj) {
                return
            }
            obj.setCoords()

            const objBoundingBox = obj.getBoundingRect()
            if (objBoundingBox.top < 0) {
                obj.set('top', 0)
                obj.setCoords()
            }
            if (objBoundingBox.left > canvas.width - objBoundingBox.width) {
                obj.set('left', canvas.width - objBoundingBox.width)
                obj.setCoords()
            }
            if (objBoundingBox.top > canvas.height - objBoundingBox.height) {
                obj.set('top', canvas.height - objBoundingBox.height)
                obj.setCoords()
            }
            if (objBoundingBox.left < 0) {
                obj.set('left', 0)
                obj.setCoords()
            }
        }

        function sendLinesToBack() {
            canvas.getObjects().map(o => {
                if (o.type === 'line') {
                    canvas.sendToBack(o)
                }
            })
        }

        document.querySelectorAll('.rectangle')[0].addEventListener('click', function () {
            const o = addRect(0, 0, 60, 60)
            canvas.setActiveObject(o)
        })

        document.querySelectorAll('.circle')[0].addEventListener('click', function () {
            const o = addCircle(0, 0, 30)
            canvas.setActiveObject(o)
        })

        document.querySelectorAll('.triangle')[0].addEventListener('click', function () {
            const o = addTriangle(0, 0, 30)
            canvas.setActiveObject(o)
        })

        document.querySelectorAll('.chair')[0].addEventListener('click', function () {
            const o = addChair(0, 0)
            canvas.setActiveObject(o)
        })


        document.querySelectorAll('.remove')[0].addEventListener('click', function () {
            const o = canvas.getActiveObject()
            if (o) {
                o.remove()
                canvas.remove(o)
                canvas.discardActiveObject()
                canvas.renderAll()
            }
        })

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

        $('#floor').on('change', function (e) {
            buildData.floor_id = this.value;
        })


        function buildSeat() {
            canvas = null;
            let selectedFloor = $('#floor').val();
            if (selectedFloor < 1) {
                alert('please select floor first.Then try to save.')
            } else {
                $.ajax({
                    url: '{{route('seat-builder.store')}}',
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
    </script>
@endsection
