import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { ButtonToolbar } from 'react-bootstrap';

import DcmModal  from '../Common/DcmModal';
import Seo  from '../Common/Seo';
import Select from 'react-select';

export default class Category extends Component {

    constructor(props) {

        super(props);

        this.isForCreate = (props.page_type || 'edit')  == 'create' ? true : false;
        this.seoOption = React.createRef();

        console.log('prop', props);
        this.state = {
            item: {
                title            : '',
                slug             : '',
                description      : '',
                icon             : '',
                seo_title        : '',
                seo_keyword      : '',
                seo_description  : '',
                status_identifier: '',
                dcm_detail_url   : ''
            },
            isLoading   : false,
            isLoading   : false,
            modalShow   : false,
            modalShowErr: false,
            errors      : [],
            status      : props.status,
            parent_id   : props.parent_id

        };

        this.handlePageTask = this.handlePageTask.bind(this);
        this.handleChange   = this.handleChange.bind(this);
        this.hasErrorFor    = this.hasErrorFor.bind(this);
        this.renderErrorFor = this.renderErrorFor.bind(this);

        this.toggle           = this.toggle.bind(this);
        this.toggleModalError = this.toggleModalError.bind(this);
    }

    componentDidMount() {


        var that = this;
        jQuery('.js-summernote:not(.js-summernote-enabled)').each((index, element) => {
            let el = jQuery(element);
            el.addClass('js-summernote-enabled').summernote({
                placeholder: 'eg: Place your content here.',
                height      : el.data('height') || 350,
                minHeight   : el.data('min-height') || null,
                maxHeight   : el.data('max-height') || null,
                callbacks: {

                    onChange: function (content) {
                        var html = content.trim();
                        if ( html ) {
                            that.setState({
                                item: {
                                    ...that.state.item,
                                    description: html
                                }
                            });
                        }
                        // fix for problem with ENTER and new paragraphs
                        if (html.substring(0, 5) !== '<div>') {
                            el.summernote('code', '<div>'+html+'<br /></div>');
                        }


                    }

                }
            });

        });

        const content = this.props.description;
        if ( content ) {
            jQuery('.js-summernote').each(function(){
                $(this).summernote('code','<div>'+content+'</div>');
            });
        }

        if ( this.isForCreate ) {

            const defaultOps = {
                title            : '',
                slug             : '',
                description          : '',
                icon             : '',
                seo_title        : '',
                seo_keyword      : {},
                seo_description  : '',
                status_identifier: '',
                dcm_detail_url   : '',
                pageindex     : this.props.pageindex || '#'
            };

            this.setState({
                item: defaultOps
            });
        } else  {
            this.setState({
                item: this.props
            });
        }



        // console.log( this.props );
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

    handlePageTask(event) {
        event.preventDefault();

        const pageItem = {...this.state.item, ...this.seoOption.current.state}

        // loading...
        this.toggleIsLoading( true);

        if ( this.isForCreate ) {

            pageItem.parent_id = this.props.parent_id;
            axios.post( window.dcmUri['resource'], pageItem)
            .then( resp => {

                if ( resp.data && resp.data.data) {
                    this.setState({
                        isLoading: false,
                        item: {
                            ...this.state.item,
                            dcm_detail_url: resp.data.data.dcm_detail_url || '#'
                        }
                    });
                    this.toggle()
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

        } else {

            pageItem.parent_id = this.props.parent_id;
            pageItem._method = 'PATCH';
            axios.put( window.dcmUri['resource'] + '/' + pageItem.id || 0, pageItem)
            .then( data => {

                this.toggleIsLoading( false);
                this.toggle()

            })
            .catch(err => {
                console.log('err', err.response.data.errors);

                this.toggleIsLoading( false);
                this.setState({
                    errors: err.response.data.errors,
                    modalShowErr: true
                });
            });
        }


    }

    handleSelectChange( identifier, option ) {
        this.setState({
            item: {
                ...this.state.item,
                [identifier]:  (option.value) ? option.value : option
            }
        });
    }

    handleChange (event) {

        this.setState({
            item: {
                ...this.state.item,
                [event.target.name]: event.target.value
            }
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

        const { errors, isLoading, item, status } = this.state;

        // console.log('status', status);
        const customStyles = {
            menu: (provided, state) => ({
                ...provided,
                zIndex: 999
            }),
            option: (provided, state) => ({
                ...provided,
                borderColor: 'red'
            }),
        }

        return (

            <div>

                            { this.state.modalShowErr ? <DcmModal
                                            content={errors}
                                            size="md"
                                            show={this.state.modalShowErr}
                                            onHide={this.toggleModalError}
                                        /> : null }

                <form acceptCharset="UTF-8" encType="multipart/form-data">
                    <div className="row push">
                        <div className="col-lg-3">
                            <p className="text-muted">
                                Category Information
                            </p>
                        </div>
                        <div className="col-lg-9 col-xl-9">
                            <div className="form-group">
                                <label htmlFor="title">
                                    Category Name <span className="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="title"
                                    className={`form-control ${this.hasErrorFor('title') ? 'is-invalid' : ''}`}
                                    placeholder="eg: Category Title"
                                    value={item.title}
                                    onChange={this.handleChange}
                                />
                                { this.renderErrorFor('title') }
                            </div>

                            <div className="form-group">
                                <label htmlFor="slug">Slug</label>
                                <input
                                    type="text"
                                    name="slug"
                                    className={`form-control ${this.hasErrorFor('slug') ? 'is-invalid' : ''}`}
                                    placeholder="eg: Enter slug here."
                                    defaultValue={item.slug}
                                    onChange={this.handleChange}
                                />
                                { this.renderErrorFor('slug') }
                            </div>
                            <div className="form-group">
                                <label htmlFor="title">
                                    Category Icon
                                </label>
                                <input
                                    type="text"
                                    name="icon"
                                    className={`form-control ${this.hasErrorFor('icon') ? 'is-invalid' : ''}`}
                                    placeholder="ex. fa fa-address-card mr-1"
                                    value={item.icon}
                                    onChange={this.handleChange}
                                />
                                { this.renderErrorFor('title') }
                                <div>
                                    <small>Use fontawesome classes or use custom font class icon</small>
                                </div>
                            </div>
                            <div className="form-group ">
                                <label htmlFor="title">
                                    App Status <span className="text-danger">*</span>
                                </label>
                                <Select
                                    styles={customStyles}
                                    options={status}
                                    defaultValue={status.filter(option => (option.value === (item.status_identifier || 'active')))}
                                    value={status.filter(option => (option.value === (item.status_identifier || 'active')))}
                                    onChange={(e) => this.handleSelectChange('status_identifier',e)}
                                />
                            </div>

                            <div className="form-group ">
                                <label htmlFor="description">Description</label>
                                <div
                                    className="form-control js-summernote"
                                    data-height="200"
                                    name="description"
                                    onChange={this.handleChange}>
                                </div>
                            </div>

                            <Seo {...this.props} ref={this.seoOption} />
                        </div>
                    </div>

                    <div className="row push">
                        <div className="col-lg-9 col-xl-9 offset-lg-3">
                            <div className="form-group">
                                <a href={item.pageindex} className="btn btn-dark mr-1">
                                    <i className="fa fa-arrow-left">
                                    </i> Back
                                </a>
                                <button type="button"
                                    className="btn btn-success"
                                    onClick={this.handlePageTask}
                                >
                                    <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                                    </i> {this.isForCreate ? 'Create Category' : 'Update Category'}
                                </button>

                                {this.isForCreate ? (
                                    <ButtonToolbar>
                                            <DcmModal
                                            content={`<span className="text-danger"><strong> ${item.title} </strong></span> successfully created.`}
                                            size="md"
                                            show={this.state.modalShow}
                                            onHide={this.toggle}
                                            onExiting={ function(){
                                                return window.location.href = `${item.dcm_detail_url}`;
                                            }}
                                        />
                                    </ButtonToolbar>
                                ) : (
                                    <ButtonToolbar>
                                            <DcmModal
                                            content={`<span className="text-danger"><strong> ${item.title} </strong></span> successfully updated.`}
                                            size="md"
                                            show={this.state.modalShow}
                                            onHide={this.toggle}
                                        />
                                    </ButtonToolbar>
                                )}

                            </div>
                        </div>

                    </div>
                </form>
            </div>

        );

    }
}

const element = document.getElementById('admin-category');
if ( element ) {

    const props = (element.dataset.props) ? JSON.parse(element.dataset.props) : JSON.parse('{}');
    const status = (element.dataset.status) ? JSON.parse(element.dataset.status) : JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }
    if ( status )  {
        delete element.dataset.status;
    }

    ReactDOM.render(<Category {...props} {...{status: status}} {...element.dataset} />, element );
}
