    <!-- Modal Upload -->
    <div class="modal fade" id="upload-modal" tabindex="-1" role="dialog" aria-labelledby="upload-modal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form id="upload-form" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="cut-modal">Do you want to upload this file?</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="form-group col-md-12">
                            <label for="project_id">Thumbnail</label>
                            <div class="containerImg" style="width: 100%;height:100%;">
                                <img class="img-thumb"
                                    style="width: 100%;height: 100%;object-fit: cover" />
                                <input name="thumbnail" type='file' class="input-file" style="display: none"
                                    accept=".jpeg,.png" />
                            </div>
    
                        </div>

                        <div class="form-group col-md-12">
                            <label for="source_name">Source Name *</label>
                            <input type="text" class="form-control" name="source_name" placeholder="Source Name" id="source-name"
                                required>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="source_text">Source Text</label>
                            <input type="text" class="form-control" name="source_text" placeholder="Source Text" id="source-text"
                                required>
                        </div>

                        <div class="form-group col-md-12">
                            <label for="tags" style="width: 100%;">Tags</label>
                            <select name="tags[]" style="width: 100%;"
                            class="tags form-control" id="tags" multiple="multiple">
                            </select>
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
    {{-- End Modal Upload --}}
