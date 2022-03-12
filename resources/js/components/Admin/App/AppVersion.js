import React from 'react';
import Dropzone from 'react-dropzone';
import { first } from 'lodash';
import axios from 'axios';
import { Button, Modal } from 'react-bootstrap';

export default class AppVersion extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            item: {
                app_id       : '',
                apk_version  : '',
                description  : '',
                external_link: '',
                apk_file_name  : '',
                apk_file     : '',
            },
            selected_delete: '',
            is_edit: false,
            isLoading: false,
            modalShow: false,
            modalShowErr: false,
            errors: [],
        }

        this.onHandleChange = this.onHandleChange.bind(this);
        this.onDropApk      = this.onDropApk.bind(this);

        this.toggle = this.toggle.bind(this);
        this.toggleModalError = this.toggleModalError.bind(this);
        this.onCancelBtn = this.onCancelBtn.bind(this);
    }

    componentDidMount() {

        this.setState({
            item: {
                ...this.state.item,
                app_id: this.props.id
            }
        });
    }


    onHandleChange( evt  ) {
        this.setState({
            item: {
                ...this.state.item,
                [event.target.name]: event.target.value
            }
        });
    }

    onClickCreateApkBtn() {

        const { item, is_edit } = this.state;
        const formData          = this.getFormData( item );

        axios.post( window.dcmUri['resource'] + '/upload-apk', formData)
        .then( resp => {
            console.log('resp', resp);
            window.location.reload();
        })
        .catch(err => {
            if ( err.response ) {
                this.toggleIsLoading( false);
                this.setState({
                    errors: err.response.data.errors,
                    modalShowErr: true
                });
            }
        })
    }

    onCancelBtn() {
        this.setState({
            item: {
                ...this.state.item,
                app_id       : '',
                apk_version  : '',
                description  : '',
                external_link: '',
                apk_file_name  : '',
                apk_file     : '',
            },
            is_edit: false
        });
    }

    onConfirmDelete( app ){

        this.toggle();
        // ask before delete
        this.setState({
            selected_delete: app
        });
    }

    deleteAppVersion(id) {

        axios.post( window.dcmUri['resource'] + '/delete-apk/' + id)
            .then( data => {
                this.setState({
                    // apps: items,
                    selected_delete: ''
                });
                this.toggle();
                window.location.reload();

            })
            .catch(err => {
                this.setState({
                    selected_delete: ''
                });
                this.toggle();
            });
    }

    onDropApk(acceptedFiles ) {

        const file = first(acceptedFiles);

        this.setState({
            item: {
                ...this.state.item,
                apk_file: file,
                apk_file_name : file.name || 'apk-file.apk'
            }
        });
    }

    onEditVersion(app) {

        this.setState({
            item: {
                ...this.state.item,
                apk_file_name : app.original_name || 'apk-file.apk',
                app_id       : app.app_id,
                apk_version  : app.identifier,
                description  : (app.description) ? app.description : '',
                external_link: (app.download_link) ? app.download_link : '',
            },
            is_edit: true
        });
    }


    getFormData(object) {
        const formData = new FormData();
        Object.keys(object).forEach(key => formData.append(key, object[key]));
        return formData;
    }

    toggle() {
        this.setState({
          modalShow: !this.state.modalShow
        });
    }

    toggleModalError() {
        this.setState({
            modalShowErr: !this.state.modalShowErr
        });
    }


    hasErrorFor (field) {
        return !!this.state.errors[field]
    }

    renderErrorFor (field) {

        if (this.hasErrorFor(field)) {
            return (
                <span className='invalid-feedback'>
                    <strong>{this.state.errors[field][0]}</strong>
                </span>
            )
        }
    }

    toggleIsLoading( isLoading = false) {
        this.setState({
            isLoading: isLoading
        });
    }

    render() {

        const { item, is_edit, selected_delete } = this.state;
        const { versions } = this.props;

        const styles = {
            alert_secondary: {
                backgroundColor: '#ffffff',
                borderColor: '#e6e6e6'
            }
        }

        let AppVersionContent = [];
        if (versions ) {
            AppVersionContent = versions.map((app, index) => (
                <div key={ index } className="col-4 col-md-4 col-lg-4">
                    <div className="card border-success mb-3" >
                        <div className="card-header">
                            <i className="fa fa-info-circle"></i>  <strong>ver. { app.identifier }</strong>
                            <span className="float-right">
                            <i className="fa fa-download"></i> <strong>APK</strong>
                            </span>
                        </div>
                        <div className="card-body text-dark">
                            <h6 className="card-title">{ app.description_formatted }</h6>
                            <p className="mb-2">
                                <i className="fa fa-fw fa-calendar-alt text-primary"></i> { app.date_formatted }
                            </p>
                            <p className="mb-2">
                                <i className="fab fa-fw fa-android text-primary"></i>  { app.size_formatted }
                            </p>
                            <button className="btn btn-sm btn-success mr-1 mb-2" type="button"
                                onClick={ this.onEditVersion.bind( this, app) }>
                                <i className="fa fa-edit"></i> Edit
                            </button>
                            <button className="btn btn-sm btn-dark mr-1 mb-2 " type="button"
                                 onClick={ this.onConfirmDelete.bind( this, app) }>
                                <i className="fa fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            ));
        }


        return (


            <div>

                <Modal show={this.state.modalShow} onHide={this.toggle}>
                    <Modal.Header closeButton>
                    <Modal.Title> Confirm App Deletion</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>Are you sure you want to delete <strong>{ selected_delete.identifier }</strong> ?</Modal.Body>
                    <Modal.Footer>
                    <Button variant="secondary" onClick={this.toggle}>
                        Close
                    </Button>
                    <Button variant="danger" onClick={this.deleteAppVersion.bind(this, selected_delete.id)}>
                        Confirm Delete
                    </Button>
                    </Modal.Footer>
                </Modal>

                <div className="mt-3">

                    <div className="alert alert-secondary alert-dismissable"  style={styles.alert_secondary} role="alert">
                        <h3 className="alert-heading font-size-h4 my-2">{ is_edit == true ? 'Update Apk' : 'Create new apk'} </h3>

                        <div className="form-group">
                            <label htmlFor="apk_version">Apk version</label>
                            <input
                                type="text"
                                name="apk_version"
                                className="form-control"
                                placeholder="eg: App apk version"
                                value={item.apk_version}
                                onChange={this.onHandleChange }
                            />
                        </div>

                        <div className="form-group">
                            <label htmlFor="description">What's new?</label>
                            <textarea
                                className="form-control"
                                name="description"
                                placeholder="eg: Add descriptions for this app version"
                                rows="3"
                                value={item.description}
                                onChange={this.onHandleChange }>
                            </textarea>
                        </div>
                        <div className="form-group">
                            <label htmlFor="current_ratings">External Downloadble Url (ex. link from third party)</label>
                            <textarea
                                className="form-control"
                                name="external_link"
                                placeholder="eg: http://thirdparty-url.com/download/direct/dcm.apk, http://another-url.com/download/dcm.apk"
                                value={item.external_link}
                                onChange={this.onHandleChange }>
                            </textarea>
                            <small className="text-danger">* Accept multiple links separated by comma or new line.</small>
                        </div>
                        <div className="form-group">
                            <label htmlFor="current_ratings">Upload Apk File</label>
                            <Dropzone
                                multiple={false}
                                onDrop={this.onDropApk}>
                                {({getRootProps, getInputProps, isDragActive, isDragReject}) => (
                                    <section>
                                    <div {...getRootProps({className: 'dropzone'})}>
                                        <input {...getInputProps()} />
                                        <p className="pt-3">
                                            {!isDragActive && 'Drag n drop your apk file here, or click to select your apk file'}
                                            {isDragActive && !isDragReject && "Drop it like it's hot!"}
                                            {isDragReject && "File type not accepted, sorry!"}
                                        </p>
                                    </div>
                                    </section>
                                )}
                            </Dropzone>

                            { item.apk_file_name ? (
                                <div>File to upload: <strong> {item.apk_file_name}</strong></div>
                            ) : null
                            }
                        </div>

                        <div className="form-group">
                            { is_edit == true  ? (
                                <button className="btn btn-danger btn-block btn-sm"
                                    type="button"
                                    onClick={ this.onCancelBtn.bind(this) }>
                                    <i className="fa fa-times"></i> Cancel

                                </button>
                            ) : null
                            }

                            <button className="btn btn-dark btn-block btn-sm"
                                type="button"
                                onClick={ this.onClickCreateApkBtn.bind(this) }>
                                <i className={`fa fa-${ is_edit == true  ? 'edit' : 'plus' } `} ></i> { is_edit == true ? 'Update Apk' : 'Create Apk' }

                            </button>
                        </div>

                    </div>



                </div>



                <h2 className="content-heading">
                    <span className="mr-2"> Lists of all apk versions</span>
                </h2>
                <div className="row">
                        { AppVersionContent.length > 0 && AppVersionContent }
                </div>

            </div>


        );
    }
}



