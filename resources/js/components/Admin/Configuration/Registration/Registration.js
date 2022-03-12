import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import DcmModal  from '../../Common/DcmModal';
import Select from 'react-select';

export default class Registration extends Component {

    constructor(props) {
        super(props);

        this.state = {
            item: {
                allow_registration       : '',
                terms_and_condition      : '',
                email_confirmation       : '',
                is_recaptcha             : '',
                recaptcha_site_key       : '',
                recaptcha_site_secret_key: '',

                recaptcha_on_login: '',
                recaptcha_on_registration: '',
                recaptcha_on_forgot_password: '',
                recaptcha_on_contactus: '',
                recaptcha_on_report_content: ''


            },
            isLoading   : false,
            isLoading   : false,
            modalShow   : false,
            modalShowErr: false,
            errors      : [],
            status      : []

        };


        this.handleOnSubmit   = this.handleOnSubmit.bind(this);
        this.handleChange     = this.handleChange.bind(this);
        this.hasErrorFor      = this.hasErrorFor.bind(this);
        this.renderErrorFor   = this.renderErrorFor.bind(this);

        this.toggleModalShow  = this.toggleModalShow.bind(this);
        this.toggleModalError = this.toggleModalError.bind(this);
    }

    componentDidMount() {

        // set default values
        this.setState({
            item: this.props,
            status: this.props.status
        });
    }

    handleOnSubmit(event) {

        event.preventDefault();
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

        const { errors, isLoading, item, status } = this.state;
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
                        <label htmlFor="allow_registration">Allow Registration</label>
                        <Select
                            options={status}
                            value={status.filter(option => (option.value === item.allow_registration))}
                            onChange={(e) => this.handleSelectChange('allow_registration',e)}
                        />
                    </div>

                    <div className="form-group">
                        <label htmlFor="terms_and_condition">Terms & Conditions</label>
                        <Select
                            options={status}
                            value={status.filter(option => (option.value === item.terms_and_condition))}
                            onChange={(e) => this.handleSelectChange('terms_and_condition',e)}
                        />
                        <div>
                            <small>The user has to confirm that he agree with terms and conditions before creating an account.</small>
                        </div>
                    </div>

                    <div className="form-group">
                        <label htmlFor="email_confirmation">Email Confirmation</label>
                        <Select
                            options={status}
                            value={status.filter(option => (option.value === item.email_confirmation))}
                            onChange={(e) => this.handleSelectChange('email_confirmation',e)}
                        />
                        <div>
                            <small>Require email confirmation from your newly registered users.</small>
                        </div>
                    </div>


                    <div className="form-group">
                        <h2 className="content-heading">
                            Google reCAPTCHA (V2 Support Only)
                        </h2>
                        <label htmlFor="is_recaptcha">reCAPTCHA V2</label>
                        <Select
                            options={status}
                            value={status.filter(option => (option.value === item.is_recaptcha) )}
                            onChange={(e) => this.handleSelectChange('is_recaptcha',e)}
                        />
                        <div>
                            <small>Enable/Disable Google reCAPTCHA V2 | <strong>Select "I'm not a robot" Checkbox</strong></small>
                        </div>
                    </div>

                    <div className=" form-group">
                        <div className="alert alert-info">
                            To utilize Google reCAPTCHA, please get your <code>Site Key</code> and <code>Secret Key </code>
                            from <a href="https://www.google.com/recaptcha/intro/index.html" target="_blank"><strong>reCAPTCHA Website</strong></a> and
                            paste the code above.
                        </div>
                    </div>
                    <div className="form-group">
                        <label htmlFor="recaptcha_site_key">Site Key</label>
                        <input type="text" className="form-control form-control-alt" id="recaptcha_site_key"
                        name="recaptcha_site_key"
                        placeholder="Enter your Site Key"
                        value={item.recaptcha_site_key}
                        onChange={ this.handleChange } />
                    </div>
                    <div className=" form-group">
                        <label htmlFor="recaptcha_site_secret_key">Secret Key</label>
                        <input type="text" className="form-control form-control-alt"
                        id="recaptcha_site_secret_key"
                        name="recaptcha_site_secret_key"
                        placeholder="Enter your Secret Key"
                        value={item.recaptcha_site_secret_key}
                        onChange={ this.handleChange } />
                    </div>

                    <div className="row">
                        <div className="col-6">
                            <div className=" form-group">
                                <label htmlFor="recaptcha_on_login">On login</label>
                                <Select
                                    options={status}
                                    value={status.filter(option => (option.value === item.recaptcha_on_login) )}
                                    onChange={(e) => this.handleSelectChange('recaptcha_on_login',e)}
                                />
                            </div>
                        </div>
                        <div className="col-6">
                            <div className=" form-group">
                                <label htmlFor="recaptcha_on_registration">On registration</label>
                                <Select
                                    options={status}
                                    value={status.filter(option => (option.value === item.recaptcha_on_registration) )}
                                    onChange={(e) => this.handleSelectChange('recaptcha_on_registration',e)}
                                />
                            </div>
                        </div>
                        <div className="col-6">
                            <div className=" form-group">
                                <label htmlFor="recaptcha_on_forgot_password">On forgot password</label>
                                <Select
                                    options={status}
                                    value={status.filter(option => (option.value === item.recaptcha_on_forgot_password) )}
                                    onChange={(e) => this.handleSelectChange('recaptcha_on_forgot_password',e)}
                                />
                            </div>
                        </div>
                        <div className="col-6">
                            <div className=" form-group">
                                <label htmlFor="recaptcha_on_contactus">On contact us form</label>
                                <Select
                                    options={status}
                                    value={status.filter(option => (option.value === item.recaptcha_on_contactus) )}
                                    onChange={(e) => this.handleSelectChange('recaptcha_on_contactus',e)}
                                />
                            </div>
                        </div>
                        <div className="col-6">
                            <div className=" form-group">
                                <label htmlFor="recaptcha_on_report_content">On report content form</label>
                                <Select
                                    options={status}
                                    value={status.filter(option => (option.value === item.recaptcha_on_report_content) )}
                                    onChange={(e) => this.handleSelectChange('recaptcha_on_report_content',e)}
                                />
                            </div>
                        </div>

                        
                    </div>



                    <div className="form-group mt-5">
                        <button type="button"
                            className="btn btn-success"
                            onClick={this.handleOnSubmit}
                        >
                            <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                            </i> Update Registration
                        </button>
                    </div>
                </form>

            </div>
        );

    }
}


const element = document.getElementById('admin-configuration-registration');
if ( element ) {

    const props  = (element.dataset.props) ? JSON.parse(element.dataset.props):   JSON.parse('{}');
    const status = (element.dataset.status) ? JSON.parse(element.dataset.status) : JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }

    if ( status )  {
        delete element.dataset.status;
    }

    ReactDOM.render(<Registration {...props} {...{status: status}} {...element.dataset} />, element );
}

