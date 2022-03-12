import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import DcmModal  from '../../Common/DcmModal';
import Select from 'react-select';

export default class Authentication extends Component {

    constructor(props) {
        super(props);

        this.state = {
            item: {
                app_purchase_code     : '',
                throttle_auth_override: '',
                allow_remember_me     : '',
                forgot_password       : '',
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

        console.log('this.props', this.props);
        // set default values
        this.setState({
            item: this.props,
            status: this.props.status || []
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
                        <label htmlFor="site_name">Allow "Remember Me"</label>
                        <Select
                            options={status}
                            value={status.filter(option => (option.value === item.allow_remember_me))}
                            onChange={(e) => this.handleSelectChange('allow_remember_me',e)}
                        />
                        <div>
                            <small>Enable/Disable 'Remember Me' checkbox be displayed on login form?</small>
                        </div>
                    </div>

                    <div className="form-group">
                        <label htmlFor="site_name">Forgot Password</label>
                        <Select
                            options={status}
                            value={status.filter(option => (option.value === item.forgot_password))}
                            onChange={(e) => this.handleSelectChange('forgot_password',e)}
                        />
                        <div>
                        <small>Enable/Disable forgot password feature.</small>
                        </div>
                    </div>

                    <h2 className="content-heading">
                        Authentication Throttling
                    </h2>

                    <div className="form-group">
                        <label htmlFor="site_name">Throttle Authentication Override</label>
                        <Select
                            isDisabled={true}
                            options={status}
                            value={status.filter(option => (option.value === item.throttle_auth_override))}
                            onChange={(e) => this.handleSelectChange('throttle_auth_override',e)}
                        />
                        <div>
                            <small> Override throttle default settings</small>
                        </div>
                    </div>

                    <div className="form-group">
                        {/*  disabled for a moment */}
                        {/* <h2 className="content-heading">
                            Throttle Limit
                        </h2> */}

                        {/* <div className="row">
                            <div className="col-4">
                                <label htmlFor="site_name">Global</label>
                                <div>
                                    <small>Throttling will monitor the overall failed login attempts across your site and can limit the affects of an attempted DDoS attack.</small>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="global_interval">Interval</label>
                                    <input type="text" className="form-control form-control-alt"
                                        id="site_name"
                                        name="site_name"
                                        placeholder="ex: 900" />
                                </div>

                                <div className="form-group">
                                    <label htmlFor="global_thresholds">Thresholds</label>
                                    <div className="input-group mb-1">
                                        <input type="text" aria-label="First name" className="form-control" />
                                        <input type="text" aria-label="Last name" className="form-control" />
                                        <div className="input-group-append">
                                            <button className="btn btn-sm btn-outline-danger"><i className="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                    <div className="input-group mb-1">
                                        <input type="text" aria-label="First name" className="form-control"/>
                                        <input type="text" aria-label="Last name" className="form-control"/>
                                    </div>
                                    <div className="input-group mb-1">
                                        <input type="text" aria-label="First name" className="form-control"/>
                                        <input type="text" aria-label="Last name" className="form-control"/>
                                    </div>
                                    <div className="input-group mb-1">
                                        <input type="text" aria-label="First name" className="form-control"/>
                                        <input type="text" aria-label="Last name" className="form-control"/>
                                    </div>
                                    <div className="input-group mb-1">
                                        <input type="text" aria-label="First name" className="form-control"/>
                                        <input type="text" aria-label="Last name" className="form-control"/>

                                    </div>
                                </div>
                            </div>

                            <div className="col-4">
                                <label htmlFor="ip_throttle">IP</label>
                                <div>
                                    <small>Throttling allows you to throttle the failed login attempts (across any account) of a given IP address.</small>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="ip_throttle_interval">Interval</label>
                                    <input type="text" className="form-control form-control-alt" id="ip_throttle_interval" name="ip_throttle_interval" placeholder="ex: 900" />
                                </div>
                                <div className="form-group">
                                    <label htmlFor="ip_throttle_thresholds">Thresholds</label>
                                    <input type="text" className="form-control form-control-alt" id="ip_throttle_thresholds" name="ip_throttle_thresholds" placeholder="ex: 5" />
                                </div>
                            </div>

                            <div className="col-4">
                                <label htmlFor="user_throttle">User</label>
                                <div>
                                    <small>Throttling allows you to throttle the login attempts on an individual user account.</small>
                                </div>
                                <div className="form-group">
                                    <label htmlFor="user_throttle_interval">Interval</label>
                                    <input type="text" className="form-control form-control-alt" id="user_throttle_interval" name="user_throttle_interval" placeholder="ex: 900" />
                                </div>
                                <div className="form-group">
                                    <label htmlFor="user_throttle_threshold">Thresholds</label>
                                    <input type="text" className="form-control form-control-alt" id="user_throttle_threshold" name="user_throttle_threshold" placeholder="ex: 5" />
                                </div>
                            </div>
                        </div> */}
                    </div>


                    <div className="form-group mt-5">
                        <button type="button"
                            className="btn btn-success"
                            onClick={this.handleOnSubmit}
                        >
                            <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                            </i> Update Authentication
                        </button>
                    </div>
                </form>

            </div>
        );

    }
}


const element = document.getElementById('admin-configuration-authentication');
if ( element ) {

    const props  = (element.dataset.props) ? JSON.parse(element.dataset.props):   JSON.parse('{}');
    const status = (element.dataset.status) ? JSON.parse(element.dataset.status) : JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }

    if ( status )  {
        delete element.dataset.status;
    }

    ReactDOM.render(<Authentication {...props} {...{status: status}} {...element.dataset} />, element );
}

