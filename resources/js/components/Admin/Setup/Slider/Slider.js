import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import DcmModal  from '../../Common/DcmModal';
import { ButtonToolbar, Tabs, Tab } from 'react-bootstrap';
import Dropzone from 'react-dropzone';
import { createFormData } from '../../Common/Utility';
import { first } from 'lodash';

export default class Slider extends Component {

    constructor(props) {
        super(props);



        this.isForCreate = (props.page_type || 'edit')  == 'create' ? true : false;
        this.seoOption = React.createRef();
        this.state = {

            item: {
                title        : '',
                link         : '',
                image        : '',
                image_preview: ''
            },
            isLoading: false,
            isLoading: false,
            modalShow: false,
            modalShowErr: false,
            errors: [],
            lists: props

        };


        this.handleOnSubmit   = this.handleOnSubmit.bind(this);
        this.handleChange   = this.handleChange.bind(this);
        this.onDropSliderImage = this.onDropSliderImage.bind(this);
        this.hasErrorFor      = this.hasErrorFor.bind(this);
        this.renderErrorFor   = this.renderErrorFor.bind(this);

    }


    componentDidMount() {

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


    handleChange (event) {

        this.setState({
            item: {
                ...this.state.item,
                [event.target.name]: event.target.value
            }
        });

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

    handleOnSubmit(event) {
        event.preventDefault();

        const { item } = this.state;

        const _itemFormData = createFormData(item);
        console.log('_itemFormData', _itemFormData);

        axios.post( window.dcmUri['resource'], _itemFormData)
        .then( resp => {

            if ( resp.data && resp.data.data) {
                this.setState({
                    isLoading: false,
                    item: {
                        ...this.state.item
                    }
                });
                this.toggle()
                window.location.reload();
            }
        })
        .catch(err => {
            console.log('err', err.response.data.errors);

            this.setState({
                errors: err.response.data.errors,
                modalShowErr: true
            });
            console.log('create', this.state);
        });

    }


    onDropSliderImage(acceptedFiles ) {

        const previewImages = acceptedFiles.map( function( item ) {
            item.preview = URL.createObjectURL(item);
            URL.revokeObjectURL(item);
            return item;
        });
        const sliderImage = first( previewImages )
        this.setState({
            item: {
                ...this.state.item,
                image: sliderImage
            }
        });
    }


    render() {

        const { errors, isLoading, item } = this.state;
        const styles = {

            avatar: {
                width: '60%'
            },
            fit_image: {
                width: 'auto',
                height: 100,
                objectFit: 'cover'
            }
        }


        return (
            <div className="mb-5 pb-5">

                <div className="mt-3 pl-2">
                    <div className="form-group">
                        <label htmlFor="title">Image Name </label>
                        <input
                            type="text"
                            name="title"
                            className={`form-control ${this.hasErrorFor('title') ? 'is-invalid' : ''}`}
                            placeholder="eg: Enter Slider title"
                            defaultValue={item.title}
                            onChange={this.handleChange}
                        />
                        { this.renderErrorFor('title') }
                    </div>


                    <div className="form-group">
                        <label htmlFor="title">Link URL </label>
                        <input
                            type="text"
                            name="link"
                            className={`form-control ${this.hasErrorFor('link') ? 'is-invalid' : ''}`}
                            placeholder="eg: Enter link when user click the image."
                            defaultValue={item.link}
                            onChange={this.handleChange}
                        />
                        { this.renderErrorFor('link') }
                    </div>

                    <div className="form-group">
                        <label htmlFor="app_link"> Upload Image Slider </label>
                        <Dropzone
                            multiple={true}
                            accept="image/*"
                            onDrop={this.onDropSliderImage}>
                            {({getRootProps, getInputProps, isDragActive, isDragReject}) => (
                                <section>
                                <div {...getRootProps({className: 'dropzone'})}>
                                    <input {...getInputProps()} />
                                    <p className="pt-3">
                                        {!isDragActive && 'Drag n drop your image here, or click to select your image'}
                                        {isDragActive && !isDragReject && "Drop it like it's hot!"}
                                        {isDragReject && "File type not accepted, sorry!"}
                                    </p>
                                </div>
                                </section>
                            )}
                        </Dropzone>
                        <div>
                            { item.image.name  }
                        </div>


                    </div>

                    <div className="form-group">
                        <button type="button"
                            className="btn btn-block btn-success"
                            onClick={this.handleOnSubmit}
                        >
                            <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                            </i> Upload Slider
                        </button>
                    </div>
                </div>



            </div>
        )
    }
}


const element = document.getElementById('admin-setup-slider');
if ( element ) {

    const props = (element.dataset.props) ? JSON.parse(element.dataset.props) : JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }
    ReactDOM.render(<Slider {...props} />, element );
}