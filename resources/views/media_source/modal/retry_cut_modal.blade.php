    <!-- Modal Cut -->
    <div class="modal fade" id="cut-modal" tabindex="-1" role="dialog" aria-labelledby="cut-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="cut-form" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="cut-modal">Do you want to retry to cut this file again?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="form-row">

                            <div class="form-group col-md-4">
                                <label for="trainsition">Transition *</label>
                                <select name="transition" class="form-control" id="transition">
                                    <option value="circleclose">
                                        circleclose
                                    </option>
                                    <option value="circlecrop">
                                        circlecrop
                                    </option>
                                    <option value="circleopen">
                                        circleopen
                                    </option>
                                    <option value="custom">
                                        custom
                                    </option>
                                    <option value="diagbl">
                                        diagbl
                                    </option>
                                    <option value="diagbr">
                                        diagbr
                                    </option>
                                    <option value="diagtl">
                                        diagtl
                                    </option>
                                    <option value="diagtr">
                                        diagtr
                                    </option>
                                    <option value="dissolve">
                                        dissolve
                                    </option>
                                    <option value="distance">
                                        distance
                                    </option>
                                    <option value="fade" selected="">
                                        fade
                                    </option>
                                    <option value="fadeblack">
                                        fadeblack
                                    </option>
                                    <option value="fadefast">
                                        fadefast
                                    </option>
                                    <option value="fadegrays">
                                        fadegrays
                                    </option>
                                    <option value="fadeslow">
                                        fadeslow
                                    </option>
                                    <option value="fadewhite">
                                        fadewhite
                                    </option>
                                    <option value="hblur">
                                        hblur
                                    </option>
                                    <option value="hlslice">
                                        hlslice
                                    </option>
                                    <option value="horzclose">
                                        horzclose
                                    </option>
                                    <option value="horzopen">
                                        horzopen
                                    </option>
                                    <option value="hrslice">
                                        hrslice
                                    </option>
                                    <option value="pixelize">
                                        pixelize
                                    </option>
                                    <option value="radial">
                                        radial
                                    </option>
                                    <option value="rectcrop">
                                        rectcrop
                                    </option>
                                    <option value="slidedown">
                                        slidedown
                                    </option>
                                    <option value="slideleft">
                                        slideleft
                                    </option>
                                    <option value="slideright">
                                        slideright
                                    </option>
                                    <option value="slideup">
                                        slideup
                                    </option>
                                    <option value="smoothdown">
                                        smoothdown
                                    </option>
                                    <option value="smoothleft">
                                        smoothleft
                                    </option>
                                    <option value="smoothright">
                                        smoothright
                                    </option>
                                    <option value="smoothup">
                                        smoothup
                                    </option>
                                    <option value="squeezeh">
                                        squeezeh
                                    </option>
                                    <option value="squeezev">
                                        squeezev
                                    </option>
                                    <option value="vdslice">
                                        vdslice
                                    </option>
                                    <option value="vertclose">
                                        vertclose
                                    </option>
                                    <option value="vertopen">
                                        vertopen
                                    </option>
                                    <option value="vuslice">
                                        vuslice
                                    </option>
                                    <option value="wipebl">
                                        wipebl
                                    </option>
                                    <option value="wipebr">
                                        wipebr
                                    </option>
                                    <option value="wipedown">
                                        wipedown
                                    </option>
                                    <option value="wipeleft">
                                        wipeleft
                                    </option>
                                    <option value="wiperight">
                                        wiperight
                                    </option>
                                    <option value="wipetl">
                                        wipetl
                                    </option>
                                    <option value="wipetr">
                                        wipetr
                                    </option>
                                    <option value="wipeup">
                                        wipeup
                                    </option>
                                    <option value="zoomin">
                                        zoomin
                                    </option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="resolution">Resolution *</label>
                                <select name="resolution" class="form-control" id="resolution">
                                    <option value="16:9">16:9</option>
                                    <option value="4:3" selected>4:3</option>
                                    <option value="1:1">1:1</option>
                                </select>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="seg_start">Segment Start *</label>
                                <input type="number" class="form-control" name="seg_start" placeholder="Segment Start"
                                    id="seg-start" required value="5">
                            </div>

                            <div class="form-group col-md-4">
                                <label for="seg_length">Segment Length *</label>
                                <input type="number" class="form-control" name="seg_length" id="seg-length"
                                    placeholder="Segment Length" required value="90">

                            </div>

                            <div class="form-group col-md-4">
                                <label for="seg_gap">Segment Gap *</label>
                                <input type="number" class="form-control" name="seg_gap" placeholder="Segment Gap"
                                    id="seg-gap" required value="25">

                            </div>

                            <div class="form-group col-md-4">
                                <label for="flip">Flip</label>
                                <select name="flip" class="form-control" id="flip">
                                    <option value="">Choose video flip..</option>
                                    <option value="hflip">hflip</option>
                                    <option value="vflip">vflip</option>
                                </select>

                            </div>

                            <div class="form-group col-md-4">
                                <label for="cut_off">Cut Off</label>
                                <select name="cut_off" class="form-control" id="cut-off">
                                    @foreach (range(0, 10) as $item)
                                        <option value="{{ $item }}">{{ $item }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="form-group col-md-4">
                                <label for="cut_off_side">Cut Off Side</label>
                                <select name="cut_off_side" class="form-control" id="cut-off-side">
                                    <option value="0">Middle/Center</option>
                                    <option value="1">Left/Top</option>
                                    <option value="2">Right/Bottom</option>
                                </select>

                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-ok">Yes</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    {{-- End Modal Cut --}}
