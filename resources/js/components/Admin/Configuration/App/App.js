import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import {Controlled as CodeMirror} from 'react-codemirror2'
require('codemirror/mode/javascript/javascript');

import DcmModal  from '../../Common/DcmModal';

export default class App extends Component {

    constructor(props) {
        super(props);

        this.state = {
            item: {
                app_purchase_code: '',
                app_username     : '',
                app_custom_css   : '',
                app_custom_js    : '',
                app_download_time: '',
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

        console.log('this.props', this.props);
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
                        <label htmlFor="app_purchase_code">Purchase Code</label>
                        <textarea className="form-control form-control-alt" rows="5"
                        id="app_purchase_code"
                        name="app_purchase_code"
                        placeholder="Enter your purchase code here.."
                        value={item.app_purchase_code}
                        onChange={this.handleChange} >
                        </textarea>
                    </div>

                    <div className="form-group">
                        <label htmlFor="app_username">Codecanyon Username</label>
                        <input type="text" className="form-control form-control-alt"
                            id="app_username"
                            name="app_username"
                            placeholder="Enter the username who bought our script."
                            value={item.app_username}
                            onChange={this.handleChange}  />

                    </div>

                    <div className="form-group">
                        <label htmlFor="app_download_time">Delay Download Time (in seconds)</label>
                        <input type="text" className="form-control form-control-alt"
                            id="app_download_time"
                            name="app_download_time"
                            placeholder="Enter the download time before start."
                            defaultValue={item.app_download_time}
                            onChange={this.handleChange}  />

                    </div>

                    <h2 className="content-heading">
                        Custom CSS ( styling ) / Javasript
                    </h2>


                    <div className="form-group">
                        <label htmlFor="app_custom_css"> Your custom CSS</label>
                        <CodeMirror
                            name="app_custom_css"
                            value={item.app_custom_css}
                            options={item.options}
                            onBeforeChange={(editor, data, value) => {
                                this.setState({
                                    item: {
                                        ...this.state.item,
                                        'app_custom_css': value
                                    }
                                });
                            }}
                            onChange={(editor, data, value) => {
                            }}
                        />
                        {/* <textarea className="form-control form-control-alt"
                            rows="10"
                            name="app_custom_css"
                            placeholder="Enter your custom css code here.."
                            value={item.app_custom_css}
                            onChange={this.handleChange}>
                        </textarea> */}
                    </div>

                    <div className="form-group">
                        <label htmlFor="app_custom_js"> Your custom Javasript</label>
                        <CodeMirror
                            name="app_custom_js"
                            value={item.app_custom_js}
                            options={item.options}
                            onBeforeChange={(editor, data, value) => {
                                this.setState({
                                    item: {
                                        ...this.state.item,
                                        'app_custom_js': value
                                    }
                                });
                            }}
                            onChange={(editor, data, value) => {
                            }}
                        />
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


const element = document.getElementById('admin-configuration-app');
if ( element ) {

    const props  = (element.dataset.props) ? JSON.parse(element.dataset.props):   JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }

    ReactDOM.render(<App {...props} {...element.dataset} />, element );
}

