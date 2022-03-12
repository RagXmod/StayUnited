import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';

import DcmModal  from '../../Common/DcmModal';
import DocumentInput  from '../../Common/DocumentInput';
import Dropzone from 'react-dropzone';
import Select from 'react-select';
import { first } from 'lodash';

export default class General extends Component {

    constructor(props) {
        super(props);

        this.state = {
            item: {
                site_name                  : '',
                site_logo                  : '',
                timezone                   : '',
                enable_ssl                 : '',
                enable_cookie_notification : '',
                supported_country_selected : '',
                supported_language_selected: '',
                social_facebook            : '',
                social_twitter             : '',
                social_google_plus         : '',
                social_pinterest           : '',
                contact_email           : '',
                add_this: '',
            },
            isLoading   : false,
            isLoading   : false,
            modalShow   : false,
            modalShowErr: false,
            errors      : [],
            status      : [],
            timezonelist: [],
            countries: [],
            languages: [],

        };

        this.handleOnSubmit = this.handleOnSubmit.bind(this);
        this.handleUpload   = this.handleUpload.bind(this);
        this.onDropRejected = this.onDropRejected.bind(this);
        this.handleChange   = this.handleChange.bind(this);
        this.hasErrorFor    = this.hasErrorFor.bind(this);
        this.renderErrorFor = this.renderErrorFor.bind(this);

        this.toggleModalShow  = this.toggleModalShow.bind(this);
        this.toggleModalError = this.toggleModalError.bind(this);

        this.addMoreSocialMedia = this.addMoreSocialMedia.bind(this);

    }


    componentDidMount() {

        console.log('this.props', this.props);

        // set default values
        this.setState({
            item        : this.props,
            status      : this.props.status,
            timezonelist: this.props.timezonelist,
            countries   : this.props.countries,
            languages   : this.props.languages
        });
    }

    addMoreSocialMedia(event) {
        console.log('more', event);


        const documents = (this.state.item.social_media_accounts || []).concat(DocumentInput);
        console.log('documents', documents);
        this.setState({
            item: {
                ...this.state.item,
                'social_media_accounts': documents
            }
        });
    }

    handleUpload( files ) {

        const file = first( files );

        // this.setState({
        //     item: {
        //         ...this.state.item,
        //         'site_logo': URL.createObjectURL(file)
        //     }
        // });

        const reader = new FileReader();
        reader.onabort = () => console.log('file reading was aborted')
        reader.onerror = () => console.log('file reading has failed')

        reader.onloadend = () => {
            const binaryStr = reader.result;
            if (binaryStr != undefined)
            {
                this.setState({
                    item: {
                        ...this.state.item,
                        'site_logo': binaryStr
                    }
                });
            }
        }
        reader.readAsDataURL(file);

    }

    handleUploadState( files ) {
        console.log('files', this.state.item);
    }

    onDropRejected(files) {
        console.log('efilesvt', files);
    }

    handleOnSubmit(event) {

        event.preventDefault();
        console.log('this.state', this.state);
        const stateItem = {...this.state.item}
        // loading...
        this.toggleIsLoading( true);

        axios.post( window.dcmUri['resource'], stateItem)
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


    handleSelectChange( identifier, option ) {
        console.log('option', option);
        this.setState({
            item: {
                ...this.state.item,
                [identifier]: option.value
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

        const { errors, isLoading, item, status, timezonelist, countries, languages} = this.state;

        // let socialMediaAccounts = [
        //     {
        //         label: 'Facebook',
        //         value: ''
        //     }
        // ];
        // // if ( item.social_media_accounts ) {
        // //     socialMediaAccounts = item.social_media_accounts.map((Element, index) => {
        // //         // return <Element key={ index } index={ index } title="" />
        // //     });
        // // }

        const styleSiteLogo = {
            maxHeight: '200px'
        }
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
                        <label htmlFor="site_name">Site Name</label>
                        <input type="text" className="form-control form-control-alt" id="site_name"
                            name="site_name"
                            placeholder="Site Name here"
                            value={item.site_name  || ''}
                            onChange={ this.handleChange } />
                    </div>


                    <div className="row">

                        <div className="col-lg-8">
                            <div className="form-group">
                                <label htmlFor="site_logo">Upload your own logo</label>
                                <Dropzone
                                    onDropAccepted={this.handleUpload}
                                    multiple={false}
                                    accept={'image/*'}
                                    onDropRejected={this.onDropRejected}>
                                    {({getRootProps, getInputProps}) => (
                                        <section>
                                        <div {...getRootProps({className: 'dropzone'})}>
                                            <input {...getInputProps()} />
                                            <p className="pt-3">Drag 'n' drop your logo here, or click to select your logo</p>
                                        </div>
                                        </section>
                                    )}
                                </Dropzone>
                                <div>
                                    <small>Allowed all image file type and accept one image only.</small>
                                </div>
                            </div>
                        </div>

                        <div className="col-lg-4">
                            <div className="form-group">
                                <label htmlFor="site_logo">Site Logo Preview</label>
                                <img src={item.site_logo || 'https://via.placeholder.com/350x150.png'} width="300px" style={styleSiteLogo} className="img-thumbnail img-responsive mt-3 mb-4" />
                            </div>
                        </div>

                    </div>

                    <div className="form-group">
                        <label htmlFor="timezone">Timezone</label>
                        <Select
                            options={timezonelist}
                            value={timezonelist.filter(option => (option.value === item.timezone || ''))}
                            onChange={(e) => this.handleSelectChange('timezone',e)}
                        />
                    </div>

                    <div className="form-group">
                        <label htmlFor="enable_ssl">Enable SSL</label>
                        <Select
                            options={status}
                            value={status.filter(option => (option.value === item.enable_ssl || ''))}
                            onChange={(e) => this.handleSelectChange('enable_ssl',e)}
                        />
                        <div>
                            <small>You should install SSL into your website before enable SSL integration. For more information about SSL, please ask your hosting company.</small>
                        </div>
                    </div>

                    <div className="form-group">
                        <label htmlFor="enable_cookie_notification">Enable Cookie Notification Bar</label>
                        <Select
                            options={status}
                            value={status.filter(option => (option.value === item.enable_cookie_notification || ''))}
                            onChange={(e) => this.handleSelectChange('enable_cookie_notification',e)}
                        />
                        <div>
                            <small>Enable cookie notification bar to your site, ensuring compliance with the EU Cookie Law. </small>
                        </div>
                    </div>

                    <div className="form-group">
                        <label htmlFor="supported_country_selected">Supported Countries</label>
                        <Select
                            options={countries}
                            value={countries.filter(option => (option.value === item.supported_country_selected || ''))}
                            onChange={(e) => this.handleSelectChange('supported_country_selected',e)}
                        />
                        <div>
                            <small>List of supported countries for Google Play Store</small>
                        </div>
                    </div>

                    <div className="form-group">
                        <label htmlFor="supported_language_selected">Supported Languages</label>
                        <Select
                            options={languages}
                            value={languages.filter(option => (option.value === item.supported_language_selected  || ''))}
                            onChange={(e) => this.handleSelectChange('supported_language_selected',e)}
                        />
                        <div>
                            <small>List of supported languages for Google Play Store</small>
                        </div>
                    </div>


                    <div className="form-group">
                        <label htmlFor="timezone">Contact Email</label>
                        <input type="email"
                            className="form-control"
                            name="contact_email"
                            value={item.contact_email}
                            placeholder="Enter your email address"
                            className={`form-control ${this.hasErrorFor('contact_email') ? 'is-invalid' : ''}`}
                            onChange={this.handleChange}
                        />
                        <div>
                            <small> Enter your email to receive contact us and other report from user.</small>
                        </div>
                    </div>


                    <div className="form-group">
                        <label htmlFor="add_this">AddThis Share Button</label>
                        <input type="text"
                            className="form-control"
                            name="add_this"
                            value={item.add_this}
                            placeholder="Enter your addthis id: Ex. ra-12b1ff7b9a536c31"
                            className={`form-control ${this.hasErrorFor('add_this') ? 'is-invalid' : ''}`}
                            onChange={this.handleChange}
                        />
                        <div>
                            <small>Please visit <a href="https://www.addthis.com" rel="nofollow" target="_new">https://www.addthis.com</a></small>
                        </div>
                    </div>


                    <h2 className="content-heading">
                        Social Media Page Accounts
                        {/* <button type="button" className="btn btn-sm btn-dark float-right"
                            onClick={ this.addMoreSocialMedia }>
                            <i className="fa fa-link mr-1"></i>Add more page</button> */}

                    </h2>

                    <div className="form-group">
                        <label htmlFor="social_facebook"> Facebook Page</label>
                        <input type="text" className="form-control form-control-alt"
                            name="social_facebook"
                            placeholder="Facebook Page"
                            value={item.social_facebook || ''}
                            onChange={ this.handleChange } />
                    </div>


                    <div className="form-group">
                        <label htmlFor="social_twitter">Twitter Page</label>
                        <input type="text" className="form-control form-control-alt"
                            name="social_twitter"
                            placeholder="Twitter Page"
                            value={item.social_twitter || ''}
                            onChange={ this.handleChange } />
                    </div>

                    <div className="form-group">
                        <label htmlFor="social_google_plus"> Google+ Page</label>
                        <input type="text" className="form-control form-control-alt"
                            name="social_google_plus"
                            placeholder="Google+ Page"
                            value={item.social_google_plus  || ''}
                            onChange={ this.handleChange } />
                    </div>

                    <div className="form-group">
                        <label htmlFor="social_pinterest"> Pinterest Page</label>
                        <input type="text" className="form-control form-control-alt"
                            id="social_pinterest"
                            name="social_pinterest"
                            placeholder="Pinterest Page"
                            value={item.social_pinterest || ''}
                            onChange={ this.handleChange } />
                    </div>

                    <div className="form-group mt-5">
                        <button type="button"
                            className="btn btn-success"
                            onClick={this.handleOnSubmit}
                        >
                            <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                            </i> Update General
                        </button>
                    </div>

                </form>

            </div>
        );

    }
}


const element = document.getElementById('admin-configuration-general');
if ( element ) {

    const props  = (element.dataset.props) ? JSON.parse(element.dataset.props):   JSON.parse('{}');
    const status = (element.dataset.status) ? JSON.parse(element.dataset.status): JSON.parse('{}');
    const timezonelist = (element.dataset.timezonelist) ? JSON.parse(element.dataset.timezonelist): JSON.parse('{}');
    const countries = (element.dataset.countries) ? JSON.parse(element.dataset.countries): JSON.parse('{}');
    const languages = (element.dataset.languages) ? JSON.parse(element.dataset.languages): JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }
    if ( status )  {
        delete element.dataset.status;
    }
    if ( timezonelist )  {
        delete element.dataset.timezonelist;
    }

    if ( countries )  {
        delete element.dataset.countries;
    }

    if ( languages )  {
        delete element.dataset.languages;
    }
    ReactDOM.render(<General {...props} {...{status: status, timezonelist: timezonelist, countries: countries, languages: languages }} {...element.dataset} />, element );
}

