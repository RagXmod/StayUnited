

import Croppie from 'croppie';


export default class ProfileAvatar {

    constructor() {

        this.update_profile_url = null;

        this.changeMyProfile();
        this.modalProfilePhotoSelection();
        this.usePhotoBtn();
        this.cancelUploadPhoto();

    }

    setUrl( uri ) {
        this.update_profile_url = uri;
    }

    profileModal(modalType = 'show') {
        jQuery('#profileAvatarModal').modal(modalType);
    }

    changeMyProfile() {

        let self = this;
        jQuery('#changeMyPhoto').click(function(){
            self.profileModal();
        });
    }

    modalProfilePhotoSelection() {
        let self = this;

        jQuery('.profile-photo').each(function(){

            $(this).click(function(){

                if ( !window.myProfile.update_profile_url )
                    throw 'No url found';

                const uploadProfileType = $(this).data('profile-type');

                const data = {
                    upload_type: uploadProfileType,
                    image_blob: null
                }

                if ( uploadProfileType == 'my-avatar') {
                    // process image first..
                    return self.changeAvatarByUpload();
                }
                return self.sendRequest( data );
            });
        });
    }


    changeAvatarByUpload() {

        let self = this;
        $('#avatar-upload').on('change', function () {
            self._readFile(this);
        });
    }


    _readFile(input) {

        let self = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                const image = e.target.result;

                self.profileModal('hide');
                self.hideCurrentImage();
                self.initializeCroppiePlugin( image );
            }


            this.showControls();
            this.changeMyPhotoBtn('hide');
            reader.readAsDataURL(input.files[0]);
        }
        else {
            alert("Sorry - you're browser doesn't support the FileReader API");
        }
    }

    sendRequest( data ) {
        axios.post( window.myProfile.update_profile_url, data).then((response)=>{

            // hide modal.
            self.profileModal('hide');
            // and reload page
            window.location.reload();
            return;

        }).catch((resp)=>{

            if ( resp.status == 'error' ) {
                throw resp.data.message;
            }

            window.location.reload();
            return;
        });
    }

    hideCurrentImage() {
        $(".avatar-wrapper .avatar-preview").hide();
    }

    changeMyPhotoBtn(type = 'show') {
        const btn = $('#changeMyPhoto');

        if ( type == 'show') {
            btn.show();
        }  else {
            btn.hide();
        }
    }


    initializeCroppiePlugin( image ) {

        const vHeight = 202;

        const avatar = $('#avatar');
        const width = $(".avatar-wrapper").width(),
            bWidth = width * 82/100,
            vWidth = bWidth * 53/100;

        if (vWidth > 250) {
            vWidth = 250;
        }

        avatar.croppie('destroy');
        const croppie = avatar.croppie({
            viewport: {
                width: vWidth,
                height: vWidth,
                type: 'circle'
            },
            boundary: {
                width: bWidth,
                height: vHeight
            }
        });

        if (image) {
            croppie.croppie('bind', {
                url: image
            });
        }
    }

    usePhotoBtn() {

        let self = this;
        $('#use-photo').click(function(){

            var croppie = $("#avatar").croppie('result');
            croppie.then(function(blob) {
                // do something with cropped blob
                const data = {
                    upload_type: 'upload-file',
                    image_blob : blob
                }
                self.sendRequest( data );
            });
        });

    }

    showControls() {
        $(".avatar-controls").removeClass('d-none').addClass('d-flex');
    }


    cancelUploadPhoto() {
        let self = this;
        $('#cancel-photo').click(function(){
            $(".avatar-wrapper .avatar-preview").show();
            $(".avatar-controls").removeClass('d-flex').addClass('d-none');
            $("#avatar").croppie('destroy');
            self.changeMyPhotoBtn('show');
        });

    }

}


// Once everything is loaded
jQuery(() => {

    // Create a new instance of ProfileAvatar
   window.myProfile = new ProfileAvatar();

});
