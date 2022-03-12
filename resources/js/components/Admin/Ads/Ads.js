import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { ButtonToolbar } from 'react-bootstrap';
import debounce from 'lodash.debounce';
import DcmModal  from '../Common/DcmModal';

export default class Ads extends Component {

    constructor(props) {

        super(props);

        this.isForCreate = (props.page_type || 'edit')  == 'create' ? true : false;
        this.seoOption = React.createRef();

        this.state = {
            item: {
                title          : '',
                identifier     : '',
                ads_code       : '',
                dcm_detail_url : ''
            },
            isLoading: false,
            isLoading: false,
            modalShow: false,
            modalShowErr: false,
            errors: []

        };

        this.handlePageTask = this.handlePageTask.bind(this);
        this.handleChange   = this.handleChange.bind(this);
        this.onKeyUpSlugify = this.onKeyUpSlugify.bind(this);
        this.hasErrorFor    = this.hasErrorFor.bind(this);
        this.renderErrorFor = this.renderErrorFor.bind(this);

        this.toggle           = this.toggle.bind(this);
        this.toggleModalError = this.toggleModalError.bind(this);

        // Delay action 300 milliseconds
        this.onChangeDebounced = debounce(this.onChangeDebounced, 300)

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
                            el.summernote('code', '<div><br /></div>');
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
                title          : '',
                identifier     : '',
                ads_code       : '',
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

        const pageItem = {...this.state.item}

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

    onKeyUpSlugify(event) {

        if (  event.target.name === 'identifier') {
            this.setState({
                item: {
                    ...this.state.item,
                    [event.target.name]: event.target.value
                }
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

        this.onChangeDebounced();
    }


    onChangeDebounced() {
        // Delayed logic goes here
        const { item } = this.state;
        if ( item.identifier ) {
            const slugifyIdentifier = this.slugify(item.identifier);
            this.setState({
                item: {
                    ...this.state.item,
                    identifier: slugifyIdentifier
                }
            });
        }
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

    slugify(string) {
        const a = 'àáäâãåăæçèéëêǵḧìíïîḿńǹñòóöôœṕŕßśșțùúüûǘẃẍÿź·/_,:;'
        const b = 'aaaaaaaaceeeeghiiiimnnnoooooprssstuuuuuwxyz______'
        const p = new RegExp(a.split('').join('|'), 'g')

        return string.toString().toLowerCase()

            .replace(/\s+/g, '_') // Replace spaces with -
            .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
            .replace(/&/g, '_and_') // Replace & with 'and'
            .replace(/[^\w\-]+/g, '') // Remove all non-word characters
            .replace(/\_\_+/g, '_') // Replace multiple - with single -
            .replace(/^_+/, '') // Trim - from start of text
            .replace(/_+$/, '') // Trim - from end of text
    }

    selectStatusOptions() {

        // let defaultValue;

        // const { item, status } = this.state;
        // const options = status.map(function(opt, i) {

        //     // // if this is the selected option, set the <select>'s defaultValue
        //     if (opt.selected === true || opt.selected === 'selected') {
        //         // if the <select> is a multiple, push the values
        //         // to an array
        //         if (this.props.multiple) {
        //             if (defaultValue === undefined) {
        //                 defaultValue = [];
        //             }
        //             defaultValue.push( opt.identifier );
        //         } else {
        //             // otherwise, just set the value.
        //             // NOTE: this means if you pass in a list of options with
        //             // multiple 'selected', WITHOUT specifiying 'multiple',
        //             // properties the last option in the list will be the ONLY item selected.
        //             defaultValue = opt.identifier;
        //         }
        //     }
        //     // // attribute schema matches <option> spec; http://www.w3.org/TR/REC-html40/interact/forms.html#h-17.6
        //     // // EXCEPT for 'key' attribute which is requested by ReactJS
        //     return <option key={i} value={opt.identifier} label={opt.title}>{opt.title}</option>;
        // }, this);

        // // set default value
        // defaultValue = item.status_identifier || defaultValue;
        // return (
        //     <select
        //         className="form-control"
        //         value={defaultValue}
        //         name="status_identifier"
        //         onChange={this.handleChange}
        //     >
        //         {options}
        //     </select>
        // )

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
                {/* <pre>{ JSON.stringify(item, null, "\t") }</pre> */}

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
                                    Ads Title <span className="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="title"
                                    className={`form-control ${this.hasErrorFor('title') ? 'is-invalid' : ''}`}
                                    placeholder="eg: Ads Title"
                                    value={item.title}
                                    onChange={this.handleChange}
                                />
                                { this.renderErrorFor('title') }
                            </div>
                            <div className="form-group">
                                <label htmlFor="identifier">
                                    Ads Identifier <span className="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="identifier"
                                    className={`form-control ${this.hasErrorFor('identifier') ? 'is-invalid' : ''}`}
                                    placeholder="eg: footer_ads or sidebar_ads"
                                    value={item.identifier}
                                    onChange={this.handleChange}
                                />
                                { this.renderErrorFor('identifier') }
                            </div>
                            {/* <div className="form-group">
                                <label htmlFor="title">
                                    Page Status <span className="text-danger">*</span>
                                </label>
                                { this.selectStatusOptions() }
                            </div> */}
                            <div className="form-group row">
                                <div className="col-lg-12">
                                    <label htmlFor="content">Ads Code Here</label>
                                    <textarea
                                     className="form-control"
                                     name="ads_code"
                                     placeholder="Place your google adsense or affiliate code"
                                     rows="15"
                                     value={item.ads_code}
                                     onChange={this.handleChange}>
                                    </textarea>
                                </div>
                            </div>

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
                                    </i> {this.isForCreate ? 'Create Ads' : 'Update Ads'}
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

const element = document.getElementById('admin-ads');
if ( element ) {

    const props = (element.dataset.props) ? JSON.parse(element.dataset.props) : JSON.parse('{}');
    if ( props )  {
        delete element.dataset.props;
    }
    ReactDOM.render(<Ads {...props} {...element.dataset} />, element );
}
