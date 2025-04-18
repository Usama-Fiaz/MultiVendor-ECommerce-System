<div class="modal fade" id="toggle-modal" tabindex="-1" aria-labelledby="toggle-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
            </div>
            <div class="modal-body px-4 px-sm-5 pt-0">
                <div class="d-flex flex-column align-items-center text-center gap-2 mb-2">
                    <img width="70" class="mb-3" id="toggle-image" alt="" class="mb-20">
                    <h5 class="modal-title" id="toggle-title"></h5>
                    <div class="text-center" id="toggle-message"></div>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn--primary min-w-120" data-dismiss="modal" id="toggle-ok-button" onclick="confirmToggle()">{{translate('ok')}}</button>
                    <button type="button" class="btn btn-danger-light min-w-120" data-dismiss="modal">{{ translate('cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="toggle-status-modal" tabindex="-1" aria-labelledby="toggle-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg">
            <div class="modal-header border-0 pb-0 d-flex justify-content-end">
                <button type="button" class="btn-close border-0" data-dismiss="modal" aria-label="Close"><i class="tio-clear"></i></button>
            </div>
            <div class="modal-body px-4 px-sm-5 pt-0">
                <div class="d-flex flex-column align-items-center text-center gap-2 mb-2">
                    <div class="toggle-modal-img-box d-flex justify-content-center align-items-end mb-3 position-relative">
                        <img id="toggle-status-image" alt="" class="mb-20">
                        <img class="status-icon" src="" alt="" width="30">
                    </div>
                    <h5 class="modal-title" id="toggle-status-title"></h5>
                    <div class="text-center" id="toggle-status-message"></div>
                </div>
                <div class="d-flex justify-content-center gap-3">
                    <button type="button" class="btn btn--primary min-w-120" data-dismiss="modal" id="toggle-status-ok-button" onclick="confirmStatusToggle()">{{translate('ok')}}</button>
                    <button type="button" class="btn btn-danger-light min-w-120" data-dismiss="modal">{{ translate('cancel') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
