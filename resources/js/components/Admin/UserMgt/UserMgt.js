import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import { ButtonToolbar, Tabs, Tab } from 'react-bootstrap';

import DcmModal  from '../Common/DcmModal';
import Checkbox  from '../Common/Checkbox';
import { uniqBy, orderBy } from 'lodash';
// import Seo  from '../Common/Seo';

export default class UserMgt extends Component {

    constructor(props) {

        super(props);

        this.isForCreate = (props.page_type || 'edit')  == 'create' ? true : false;
        // this.seoOption = React.createRef();

        this.state = {
            item: {
                email                : '',
                username             : '',
                first_name           : '',
                last_name            : '',
                password             : '',
                password_confirmation: '',
                avatar               : '',
                status               : '',
                about_me             : '',
                status_identifier    : '',

                seo_title      : '',
                seo_description: '',
                seo_keyword    : '',
                dcm_detail_url : '',
                roles          : '',
            },
            isLoading   : false,
            isLoading   : false,
            modalShow   : false,
            modalShowErr: false,
            errors      : [],
            status      : (props.status || []),
            all_roles   : (props.all_roles || [])

        };

        this.handleOnSubmit = this.handleOnSubmit.bind(this);
        this.handleChange   = this.handleChange.bind(this);
        this.hasErrorFor    = this.hasErrorFor.bind(this);
        this.renderErrorFor = this.renderErrorFor.bind(this);

        this.toggle           = this.toggle.bind(this);
        this.toggleModalError = this.toggleModalError.bind(this);


        this.handleAllChecked        = this.handleAllChecked.bind(this);
        this.handleCheckChildElement = this.handleCheckChildElement.bind(this);
    }

    componentDidMount() {

        if ( this.isForCreate ) {

            const defaultOps = {
                email                : '',
                username             : '',
                first_name           : '',
                last_name            : '',
                password             : '',
                password_confirmation: '',
                avatar               : '',
                status               : '',
                user_detail          : {},
                about_me             : '',
                status_identifier    : this.selectStatusOptions( true),
                roles                : '',
                pageindex            : this.props.pageindex || '#'
            };

            // do nothing for now...
            this.setState({
                item: defaultOps
            });
        } else  {


            const { roles, all_roles } = this.props;

            let allRoles = all_roles;
            if ( roles && roles.length > 0 ) {

                roles.forEach(items => items.isChecked = true);
                allRoles = orderBy( uniqBy([...roles.concat(allRoles)], 'id'), 'id', 'asc');
            }


            this.setState({
                item: this.props,
                all_roles: allRoles
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

    handleAllChecked(event) {
        let allRoles = this.state.all_roles;

        allRoles.forEach(items => items.isChecked = event.target.checked)
        this.setState({
            item: {
                ...this.state.item,
                roles: allRoles
            }
        })
     }

    handleCheckChildElement( event ) {
        let allRoles = this.state.all_roles

        allRoles.forEach(item => {
            if (item.id === parseInt(event.target.value))
                item.isChecked =  event.target.checked

        });

        const filtered = allRoles.filter( item => {
            return item.isChecked === true;
        });

        this.setState({
            item: {
                ...this.state.item,
                roles: filtered
            }
        })
    }

    handleOnSubmit(event) {
        event.preventDefault();

        const pageItem = {...this.state.item}


        if (pageItem.password !== pageItem.password_confirmation) {
            this.setState({
                errors: 'The password confirmation does not match.'
            });
            this.toggleModalError();
            return false;
        }
        // loading...
        this.toggleIsLoading( true);


        // remove uneeded data
        delete pageItem.all_roles;
        delete pageItem.status;


        pageItem.user_detail.about_me = pageItem.about_me;


        console.log('this.state', pageItem);
        if ( this.isForCreate ) {

            axios.post( window.dcmUri['resource'], pageItem)
            .then( resp => {

                if ( resp.data && resp.data.data) {
                    this.setState({
                        isLoading: false,
                        item: {
                            ...this.state.item,
                            full_name: resp.data.data.full_name,
                            dcm_detail_url: resp.data.data.dcm_detail_url || '#'
                        }
                    });
                    this.toggle()
                }
            })
            .catch(err => {
                console.log('err', err.response.data.errors);

                this.toggleIsLoading( false);
                this.setState({
                    errors: err.response.data.errors,
                    modalShowErr: true
                });
                console.log('create', this.state);
            });

        } else {

            axios.put( window.dcmUri['resource'] + '/' + pageItem.id || 0, pageItem)
            .then( resp => {

                if ( resp.data && resp.data.data) {
                    this.setState({
                        isLoading: false,
                        item: {
                            ...this.state.item,
                            full_name: resp.data.data.full_name,
                            dcm_detail_url: resp.data.data.dcm_detail_url || '#'
                        }
                    });
                    this.toggleIsLoading( false);
                    this.toggle()
                }

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


    selectStatusOptions(returnDefault = false) {

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
        if ( returnDefault === true)
            return defaultValue;

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

        const { errors, isLoading, item, all_roles } = this.state;

        return (
            <div>
                {/* <pre>{ JSON.stringify(item, null, "\t") }</pre> */}

                { this.state.modalShowErr ? <DcmModal
                                            title="User Notifications"
                                            content={errors}
                                            size="md"
                                            show={this.state.modalShowErr}
                                            onHide={this.toggleModalError}
                                        /> : null }

                <form acceptCharset="UTF-8" encType="multipart/form-data">
                    <div className="row push">
                        <div className="col-lg-3">
                            <div className="avatar-wrapper">

                                <div id="avatar"></div>

                                <div className="text-center">
                                    <div className="avatar-preview" >
                                        <img className="avatar rounded-circle img-thumbnail img-responsive mt-3 mb-4" src={ item.avatar || '/img/profile.png'} alt={item.full_name} />
                                        <h5 className="text-muted">My Avatar</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div className="col-lg-9 col-xl-9">

                            <Tabs
                                id="controlled-tab-example"
                                activeKey={this.state.key}
                                onSelect={key => this.setState({ key })}
                            >
                                <Tab eventKey="user_details" title="User Details" name="user_tab">
                                    <div className="mt-3 pl-2">
                                        <div className="form-group">
                                            <label htmlFor="status">
                                                Status <span className="text-danger">*</span>
                                            </label>
                                            { this.selectStatusOptions() }
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="first_name">First Name</label>
                                            <input
                                                type="text"
                                                name="first_name"
                                                className={`form-control ${this.hasErrorFor('first_name') ? 'is-invalid' : ''}`}
                                                placeholder="eg: Your First Name"
                                                value={item.first_name}
                                                onChange={this.handleChange}
                                            />
                                            { this.renderErrorFor('first_name') }
                                        </div>
                                        <div className="form-group">
                                            <label htmlFor="last_name">Last Name</label>
                                            <input
                                                type="text"
                                                name="last_name"
                                                className={`form-control ${this.hasErrorFor('last_name') ? 'is-invalid' : ''}`}
                                                placeholder="eg: Your First Name"
                                                value={item.last_name}
                                                onChange={this.handleChange}
                                            />
                                            { this.renderErrorFor('first_name') }
                                        </div>


                                        <div className="form-group">
                                            <label htmlFor="about_me">About Me | <small> ( Short information about you. )</small></label>
                                            <textarea className="form-control"
                                                name="about_me"
                                                rows="5"
                                                value={item.about_me || ''}
                                                onChange={this.handleChange}
                                                placeholder="eg: Short description about yourself.">
                                            </textarea>
                                        </div>

                                    </div>
                                </Tab>
                                <Tab eventKey="login_details" title="Login Details">
                                    <div className="mt-3 pl-2">
                                        <div className="form-group">
                                            <label htmlFor="email">Email</label>
                                            <input type="email"
                                                className="form-control"
                                                name="email"
                                                value={item.email}
                                                placeholder="Email Address"
                                                className={`form-control ${this.hasErrorFor('email') ? 'is-invalid' : ''}`}
                                                onChange={this.handleChange}
                                            />
                                        </div>
                                    </div>
                                    <div className="mt-3 pl-2">
                                        <div className="form-group">
                                            <label htmlFor="username">Username</label>
                                            <input type="username"
                                                className="form-control"
                                                name="username"
                                                placeholder="Username"
                                                value={item.username}
                                                className={`form-control ${this.hasErrorFor('username') ? 'is-invalid' : ''}`}
                                                onChange={this.handleChange}
                                            />
                                        </div>
                                    </div>

                                    <div className="mt-3 pl-2">
                                        <div className="form-group">
                                            <label htmlFor="password">New Password</label>
                                            <input type="password"
                                                className="form-control"
                                                name="password"
                                                placeholder="Leave field blank if you don't want to change it"
                                                className={`form-control ${this.hasErrorFor('password') ? 'is-invalid' : ''}`}
                                                onChange={this.handleChange}
                                            />
                                        </div>
                                    </div>

                                    <div className="mt-3 pl-2">
                                        <div className="form-group">
                                            <label htmlFor="password_confirmation">Confirm New Password</label>
                                            <input type="password"
                                                className="form-control"
                                                name="password_confirmation"
                                                placeholder="Leave field blank if you don't want to change it"
                                                className={`form-control ${this.hasErrorFor('password_confirmation') ? 'is-invalid' : ''}`}
                                                onChange={this.handleChange}
                                            />
                                        </div>
                                    </div>
                                </Tab>

                                <Tab eventKey="user_roles" title="Permission / Roles" name="user_roles">
                                    <div className="mt-3 pl-2">
                                        <h2 className="content-heading mb-3 px-2">
                                            <input className="mr-2" type="checkbox" onChange={this.handleAllChecked}  value="checkedall" /> Check / Uncheck All
                                        </h2>
                                        <Checkbox {...{ collections: all_roles}} handleCheckChildElement={this.handleCheckChildElement} />
                                    </div>
                                </Tab>
                            </Tabs>
                        </div>
                    </div>
                    <div className="row push">
                        <div className="col-lg-9 col-xl-8 offset-lg-3">
                            <div className="form-group">
                                <a href={item.pageindex} className="btn btn-dark mr-1">
                                    <i className="fa fa-arrow-left">
                                    </i> Back
                                </a>
                                <button type="button"
                                    className="btn btn-success"
                                    onClick={this.handleOnSubmit}
                                >
                                    <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                                    </i> {this.isForCreate ? 'Create New User' : 'Update User'}
                                </button>

                                {this.isForCreate ? (
                                    <ButtonToolbar>
                                            <DcmModal
                                            content={`<span className="text-danger"><strong> ${item.full_name} </strong></span> successfully created.`}
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
                                            content={`<span className="text-danger"><strong> ${item.full_name} </strong></span> successfully updated.`}
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

const element = document.getElementById('admin-user');
if ( element ) {

    const props  = (element.dataset.props) ? JSON.parse(element.dataset.props):   JSON.parse('{}');
    const status = (element.dataset.status) ? JSON.parse(element.dataset.status): JSON.parse('{}');
    const roles  = (element.dataset.roles) ? JSON.parse(element.dataset.roles):   JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }
    if ( status )  {
        delete element.dataset.status;
    }
    if ( roles )  {
        delete element.dataset.roles;
    }

    ReactDOM.render(<UserMgt {...props} {...{status: status, all_roles: roles}} {...element.dataset} />, element );
}
