import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { ButtonToolbar } from 'react-bootstrap';

import DcmModal  from '../Common/DcmModal';
import Seo  from '../Common/Seo';

export default class Page extends Component {

    constructor(props) {

        super(props);

        this.isForCreate = (props.page_type || 'edit')  == 'create' ? true : false;
        this.seoOption = React.createRef();

        this.state = {
            item: {
                title          : '',
                slug           : '',
                content        : '',
                icon           : '',
                seo_title      : '',
                seo_description: '',
                seo_keyword      : {},
                dcm_detail_url : ''
            },
            isLoading: false,
            isLoading: false,
            modalShow: false,
            modalShowErr: false,
            errors: [],
            status: props.status

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
                                    content: html
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

        const content = this.props.content;
        if ( content ) {
            jQuery('.js-summernote').each(function(){
                $(this).summernote('code','<div>'+content+'</div>');
            });
        }

        if ( this.isForCreate ) {

            const defaultOps = {
                title         : '',
                slug          : '',
                content       : '',
                pageindex     : this.props.pageindex || '#'
            };

            // do nothing for now...
            this.setState({
                item: defaultOps
            });
        } else  {
            this.setState({
                item: this.props
            });
        }



        console.log( this.props );
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


    selectStatusOptions() {

        let defaultValue;

        const { item, status } = this.state;
        const options = status.map(function(opt, i) {

            // // if this is the selected option, set the <select>'s defaultValue
            if (opt.selected === true || opt.selected === 'selected') {
                // if the <select> is a multiple, push the values
                // to an array
                if (this.props.multiple) {
                    if (defaultValue === undefined) {
                        defaultValue = [];
                    }
                    defaultValue.push( opt.identifier );
                } else {
                    // otherwise, just set the value.
                    // NOTE: this means if you pass in a list of options with
                    // multiple 'selected', WITHOUT specifiying 'multiple',
                    // properties the last option in the list will be the ONLY item selected.
                    defaultValue = opt.identifier;
                }
            }
            // // attribute schema matches <option> spec; http://www.w3.org/TR/REC-html40/interact/forms.html#h-17.6
            // // EXCEPT for 'key' attribute which is requested by ReactJS
            return <option key={i} value={opt.identifier} label={opt.title}>{opt.title}</option>;
        }, this);

        // set default value
        defaultValue = item.status_identifier || defaultValue;
        return (
            <select
                className="form-control"
                value={defaultValue}
                name="status_identifier"
                onChange={this.handleChange}
            >
                {options}
            </select>
        )

    }


    toggleIsLoading( isLoading = false) {
        this.setState({
            isLoading: isLoading
        });
    }


    render() {

        const { errors, isLoading, item } = this.state;

        return (
            <div>
                {/* <pre>{ JSON.stringify(errors, null, "\t") }</pre> */}

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
                                Page Information
                            </p>
                        </div>
                        <div className="col-lg-9 col-xl-9">
                            <div className="form-group">
                                <label htmlFor="title">
                                    Page Name <span className="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="title"
                                    className={`form-control ${this.hasErrorFor('title') ? 'is-invalid' : ''}`}
                                    placeholder="eg: About us"
                                    value={item.title}
                                    onChange={this.handleChange}
                                />
                                { this.renderErrorFor('title') }
                            </div>
                            <div className="form-group">
                                <label htmlFor="slug">
                                    Slug <span className="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="slug"
                                    className={`form-control ${this.hasErrorFor('slug') ? 'is-invalid' : ''}`}
                                    placeholder="eg: about-us"
                                    value={item.slug}
                                    onChange={this.handleChange}
                                />
                                { this.renderErrorFor('slug') }
                            </div>
                            <div className="form-group">
                                <label htmlFor="title">
                                    Page Status <span className="text-danger">*</span>
                                </label>
                                { this.selectStatusOptions() }
                            </div>

                            <div className="form-group">
                                <label htmlFor="title">
                                    Page Icon
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
                            <div className="form-group row">
                                <div className="col-lg-12">
                                    <label htmlFor="content">Content</label>
                                    <div
                                        className="form-control js-summernote"
                                        data-height="600"
                                        name="content"
                                        onChange={this.handleChange}>
                                    </div>
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
                                    </i> {this.isForCreate ? 'Create Page' : 'Update Page'}
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

const element = document.getElementById('admin-page');
if ( element ) {

    const props = (element.dataset.props) ? JSON.parse(element.dataset.props) : JSON.parse('{}');
    const status = (element.dataset.status) ? JSON.parse(element.dataset.status) : JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }
    if ( status )  {
        delete element.dataset.status;
    }

    ReactDOM.render(<Page {...props} {...{status: status}} {...element.dataset} />, element );
}
