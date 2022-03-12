import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import DcmModal  from '../../Common/DcmModal';
import InputTag from '../../Common/InputTag';

export default class Seo extends Component {

    constructor(props) {
        super(props);

        this.state = {
            item: {
                meta_title      : '',
                meta_description: '',
                meta_keywords   : '',
                site_author     : '',
                site_author_link: '',
                title           : 'seo'
            },
            isLoading   : false,
            isLoading   : false,
            modalShow   : false,
            modalShowErr: false,
            errors      : []

        };


        this.handleOnSubmit   = this.handleOnSubmit.bind(this);
        this.handleChange     = this.handleChange.bind(this);
        this.onInputTagChange = this.onInputTagChange.bind(this);
        this.hasErrorFor      = this.hasErrorFor.bind(this);
        this.renderErrorFor   = this.renderErrorFor.bind(this);

        this.toggleModalShow  = this.toggleModalShow.bind(this);
        this.toggleModalError = this.toggleModalError.bind(this);
    }

    componentDidMount() {

        // console.log('this.props', this.props);
        // set default values
        this.setState({
            item: this.props
        });
    }

    handleOnSubmit(event) {

        event.preventDefault();
        const { item } =  this.state
        // loading...
        this.toggleIsLoading( true);

        axios.post( window.dcmUri['resource'], item)
            .then( resp => {

                if ( resp.data && resp.data.data) {
                    this.setState({
                        isLoading: false,
                        item: {
                            ...this.state.item
                        }
                    });
                    this.toggleModalShow();
                }
            })
            .catch(err => {
                console.log('err', err.response.data.errors);
                this.setState({
                    errors: err.response.data.errors,
                    modalShowErr: true
                });
            });

    }

    handleChange(event) {

        this.setState({
            item: {
                ...this.state.item,
                [event.target.name]: event.target.value
            }
        });
    }

    onInputTagChange(evt) {
        this.setState({
            item: {
                ...this.state.item,
                meta_keywords: evt
            }
        });
    }


    toggleIsLoading( isLoading = false) {
        this.setState({
            isLoading: isLoading
        });
    }

    toggleModalShow() {
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


                { this.state.modalShow ? <DcmModal
                                content={`<span className="text-danger"><strong> Configuration for ${item.title} </strong></span> successfully updated.`}
                                size="md"
                                show={this.state.modalShow}
                                onHide={this.toggleModalShow}
                            /> : null }

                <form className="mb-5"  acceptCharset="UTF-8" encType="multipart/form-data">

                    <div className="form-group">
                        <label htmlFor="meta_title">Site Title</label>
                        <input type="text" className="form-control form-control-alt"
                                id="meta_title"
                                name="meta_title"
                                value={item.meta_title}
                                placeholder="Site Title"
                                onChange={this.handleChange} />
                    </div>

                    <div className="form-group">
                        <label htmlFor="meta_description">Meta Description</label>
                        <textarea className="form-control form-control-alt" rows="5"
                        id="meta_description"
                        name="meta_description"
                        placeholder="Meta Description"
                        value={item.meta_description}
                        onChange={this.handleChange} >
                        </textarea>
                    </div>

                    <div className="form-group">
                        <label htmlFor="meta_keywords">Meta Keywords</label>
                        <InputTag
                            {...{value: this.props.meta_keywords}}
                            onChange={this.onInputTagChange} />
                    </div>

                    <div className="form-group">
                        <label htmlFor="site_author">Site Author</label>
                        <input type="text" className="form-control form-control-alt" id="site_author"
                                name="site_author"
                                value={item.site_author}
                                placeholder="Site Author"
                                onChange={this.handleChange} />
                    </div>

                    <div className="form-group">
                        <label htmlFor="site_author_link">Site Author Url</label>
                        <input type="text" className="form-control form-control-alt"
                        id="site_author_link"
                        name="site_author_link"
                        value={item.site_author_link}
                        placeholder="Site Author"
                        onChange={this.handleChange} />
                    </div>


                    <div className="form-group mt-5">
                        <button type="button"
                            className="btn btn-success"
                            onClick={this.handleOnSubmit}
                        >
                            <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                            </i> Update SEO
                        </button>
                    </div>
                </form>

            </div>
        );

    }
}


const element = document.getElementById('admin-configuration-seo');
if ( element ) {

    const props  = (element.dataset.props) ? JSON.parse(element.dataset.props):   JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }

    ReactDOM.render(<Seo {...props} {...element.dataset} />, element );
}

