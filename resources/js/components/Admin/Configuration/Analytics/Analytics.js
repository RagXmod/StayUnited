import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import DcmModal  from '../../Common/DcmModal';
import {Controlled as CodeMirror} from 'react-codemirror2'
require('codemirror/mode/javascript/javascript');

export default class Analytics extends Component {

    constructor(props) {
        super(props);

        this.state = {
            item: {
                site_analytics: '',
                site_verification: '',
                options          : {
                    mode       : 'javascript',
                    theme      : 'material',
                    indentWithTabs: true,
                    smartIndent: true,
                    tabSize: 4,
                    lineNumbers: true
                  }

            },
            isLoading   : false,
            isLoading   : false,
            modalShow   : false,
            modalShowErr: false,
            errors      : []

        };


        this.handleOnSubmit   = this.handleOnSubmit.bind(this);
        this.handleChange     = this.handleChange.bind(this);
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
                        <label htmlFor="site_analytics">Google Analytics</label>
                        {/* <textarea className="form-control form-control-alt" rows="10"
                        id="site_analytics"
                        name="site_analytics"
                        placeholder="Enter google analytics full code here."
                        value={item.site_analytics}
                        onChange={this.handleChange} >
                        </textarea> */}

                        <CodeMirror
                            name="site_analytics"
                            placeholder="Enter google analytics full code here."
                            value={item.site_analytics}
                            options={item.options}
                            onBeforeChange={(editor, data, value) => {
                                this.setState({
                                    item: {
                                        ...this.state.item,
                                        'site_analytics': value
                                    }
                                });
                            }}
                            onChange={(editor, data, value) => {
                            }}
                        />
                        <div>
                            <small>Enter google analytics full code here</small>
                        </div>
                    </div>

                    <div className="form-group">
                        <label htmlFor="site_verification">Site Verification</label>
                        <textarea className="form-control form-control-alt"
                        rows="5" placeholder="Enter google site verification code here."
                        id="site_verification"
                        name="site_verification"
                        value={item.site_verification}
                        onChange={this.handleChange} >
                        </textarea>
                    </div>


                    <div className="form-group mt-5">
                        <button type="button"
                            className="btn btn-success"
                            onClick={this.handleOnSubmit}
                        >
                            <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                            </i> Update Analytics
                        </button>
                    </div>
                </form>

            </div>
        );

    }
}


const element = document.getElementById('admin-configuration-analytics');
if ( element ) {

    const props  = (element.dataset.props) ? JSON.parse(element.dataset.props):   JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }

    ReactDOM.render(<Analytics {...props} {...element.dataset} />, element );
}

