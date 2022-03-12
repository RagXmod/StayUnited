import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import ReactPaginate from 'react-paginate';
import Progress from "react-progress-2";
import 'react-progress-2/main.css';
import DcmModal  from '../Common/DcmModal';
import Seo  from '../Common/Seo';
import { ButtonToolbar } from 'react-bootstrap';
import { createFormData } from '../Common/Utility';
import Checkbox  from '../Common/Checkbox';
import { uniqBy, orderBy, debounce } from 'lodash';
import Select from 'react-select';

export default class FeaturedApp extends Component {

    constructor(props) {
        super(props);

        console.log('props', props)
        this.isForCreate = (props.page_type || 'edit')  == 'create' ? true : false;
        this.seoOption = React.createRef();
        this.state = {
            item: {
                title          : '',
                slug           : '',
                description    : '',
                icon           : '',
                seo_title      : '',
                seo_description: '',
                seo_keyword    : '',
                status_identifier: '',
                dcm_detail_url : '',
                apps           : [],
            },
            status       : props.status,
            selected_apps: [],
            searchApps   : [],
            pageCount    : 1,
            currentPage  : 1,
            search_input : '',
            isLoading    : false,
            modalShow    : false,
            modalShowErr : false,
            errors       : []
        };


        this.handleChange                = this.handleChange.bind(this);
        this.onSearchChange              = this.onSearchChange.bind(this);
        this.onSearchChangeDebounce      = debounce(this.onSearchChangeDebounce, 500)
        this.handleAppPaginateClick      = this.handleAppPaginateClick.bind(this);
        this.handleAllChecked            = this.handleAllChecked.bind(this);
        this.handleCheckChildElement     = this.handleCheckChildElement.bind(this);
        this.addSelectedAppToCollections = this.addSelectedAppToCollections.bind(this);
        this.onCreateFeaturedAppPost     = this.onCreateFeaturedAppPost.bind(this);

        this.toggle           = this.toggle.bind(this);
        this.toggleModalError = this.toggleModalError.bind(this);

    }

    getQueryStringValue(key) {
		const value = decodeURIComponent(
			window.location.search.replace(
				new RegExp(
					'^(?:.*[&\\?]' +
						encodeURIComponent(key).replace(/[\.\+\*]/g, '\\$&') +
						'(?:\\=([^&]*))?)?.*$',
					'i'
				),
				'$1'
			)
		);
		return value ? value : null;
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

        const description = this.props.description;
        if ( description ) {
            jQuery('.js-summernote').each(function(){
                $(this).summernote('code','<div>'+description+'</div>');
            });
        }

        console.log('this.isForCreate', this.isForCreate);
        if ( this.isForCreate ) {

            const defaultOps = {
                title          : '',
                slug           : '',
                description    : '',
                icon           : '',
                seo_title      : '',
                seo_description: '',
                seo_keyword    : '',
                status_identifier: '',
                dcm_detail_url : '',
                apps           : [],
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


        const page = this.getQueryStringValue('page');
		this.setState(() => ({
            currentPage: page ? page : 1
         }))
        // console.log( this.props );
    }

    handleChange (event) {

        this.setState({
            item: {
                ...this.state.item,
                [event.target.name]: event.target.value
            }
        });

    }

    onSearchChange(evt) {
        const searchInput = evt.target.value || '';
        this.setState({
            search_input: searchInput
        });
        this.onSearchChangeDebounce( searchInput );
    }

    onSearchChangeDebounce( value ) {
        this.getAppData();
    }

    onSearchClear() {
        this.setState({
            search_input: '',
            searchApps: []
        });
    }


    toggleIsLoading( isLoading = false) {
        this.setState({
            isLoading: isLoading
        });
    }

    async handleAppPaginateClick(data) {

		const page = data.selected >= 0 ? data.selected + 1 : 0;
		await Promise.resolve(this.setState(() => ({ currentPage: page })));

		this.getAppData();
	}

    async getAppData() {

        Progress.show();

        const filters = '?page=' + this.state.currentPage
                + '&status=active'
                + '&q=' + this.state.search_input ;


        const resp = await axios.get( window.dcmUri['app_resource'] + filters)

        try {
            if (resp.data.status == 'success') {

                const { data, meta } = resp.data;

                this.setState(() => ({
                    searchApps : data,
                    currentPage: meta.pagination.current_page,
                    pageCount  : meta.pagination.total_pages
                }))
                Progress.hide();
            } else {
                Progress.hide();
                console.log(error);
            }
        } catch (error) {
            Progress.hide();
            console.log(error);
        }
    }


    handleAllChecked(event) {
        let allApps = this.state.searchApps;
        allApps.forEach(items => items.isChecked = event.target.checked)
        this.setState({
            selected_apps: allApps
        })
     }

    handleCheckChildElement( event ) {
        let allApps = this.state.searchApps

        allApps.forEach(item => {
            if (item.id === parseInt(event.target.value))
                item.isChecked =  event.target.checked

        });

        const filtered = allApps.filter( item => {
            return item.isChecked === true;
        });

        if ( filtered.length > 0) {

            // filtered = filtered.map( item => {
            //     return {
            //         id              : item.id,
            //         title_with_limit: item.title_with_limit,
            //         app_image_url   : item.app_image_url,
            //         isChecked       : item.isChecked
            //     }
            // })

            this.setState({
                selected_apps: filtered
            })
        }

    }

    addSelectedAppToCollections(event) {
        const { selected_apps, item } = this.state

        if ( selected_apps.length > 0) {

            let allApps = orderBy( uniqBy([...item.apps.concat(selected_apps)], 'id'), 'id', 'asc');

            allApps = allApps.map( item => {
                return {
                    id              : item.id,
                    title_with_limit: item.title_with_limit || item.title,
                    app_image_url   : item.app_image_url,
                    isChecked       : item.isChecked
                }
            })

            this.setState({
                item: {
                    ...this.state.item,
                    apps: allApps
                }
            });

            this.onSearchClear()
        }
    }

    onCreateFeaturedAppPost(event) {

        const item = {...this.state.item, ...this.seoOption.current.state}

       // loading...
        this.toggleIsLoading( true);

        // remove uneeded data
        delete item.status;

        const _itemFormData = createFormData(item);

        if ( this.isForCreate ) {

            axios.post( window.dcmUri['resource'], _itemFormData)
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
                // console.log('err', err.response.data.errors);

                this.toggleIsLoading( false);
                this.setState({
                    errors: err.response.data.errors,
                    modalShowErr: true
                });
                // console.log('create', this.state);
            });

        } else {

            _itemFormData.append('_method', 'PATCH')
            axios.post( window.dcmUri['resource'] + '/' + item.id || 0, _itemFormData)
            .then( resp => {

                if ( resp.data && resp.data.data) {
                    // this.setState({
                    //     isLoading: false,
                    //     item: {
                    //         ...this.state.item,
                    //         dcm_detail_url: resp.data.data.dcm_detail_url || '#'
                    //     }
                    // });
                    this.toggleIsLoading( false);
                    this.toggle()

                }

            })
            .catch(err => {

                if ( err.response ) {
                    this.toggleIsLoading( false);
                    this.setState({
                        errors: err.response.data.errors,
                        modalShowErr: true
                    });
                }

            });
        }

    }


    onDeleteConnectedApps(index, app) {
        this.setState({
            item: {
                ...this.state.item,
                apps: this.state.item.apps.filter((s, sidx) => index !== sidx)
            }
        });
    }

    handleSelectChange( identifier, option ) {
        this.setState({
            item: {
                ...this.state.item,
                [identifier]:  (option.value) ? option.value : option
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

    render() {

        const { errors, isLoading, item, searchApps, status } = this.state;

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

        let connectedAppContent = [];
        if ( item.apps ) {
            connectedAppContent = item.apps.map((app, index) => (
                <tr key={index}>
                    {/* <td>
                     <input
                        type="checkbox"
                        checked={app.selected || false}
                        onChange={ this.onTableCheckboxSelected }
                        value={app.id} />
                    </td> */}
                    <td>
                        <img src={ app.app_image_url || '/img/default-app.png' } className="img-thumbnail" alt={ app.title_with_limit }  width="40"/>
                    </td>
                    <td>
                        { app.title_with_limit  || app.title}
                    </td>
                    <td>
                        <button type="button"
                            onClick={ this.onDeleteConnectedApps.bind(this, index, app) }
                        ><i className="fa fa-trash"></i> </button>
                    </td>
                </tr>
            ));
        }


        return (
            <div>
                <Progress.Component
                    style={{ background: '#00a680', height: '5px' }}
                    thumbStyle={{ background: '#00a680', height: '5px' }}
                />
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
                                Featured App Information
                            </p>
                        </div>

                        <div className="col-lg-9 col-xl-9">
                            <div className="form-group">
                                <label htmlFor="title">
                                    Featured Post Title <span className="text-danger">*</span>
                                </label>
                                <input
                                    type="text"
                                    name="title"
                                    className={`form-control ${this.hasErrorFor('title') ? 'is-invalid' : ''}`}
                                    placeholder="eg: Feateured Post Title"
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
                                    placeholder="eg: featured-post-title"
                                    value={item.slug}
                                    onChange={this.handleChange}
                                />
                                { this.renderErrorFor('slug') }
                            </div>

                            <div className="form-group">
                                <label htmlFor="title">
                                    Featured App Status <span className="text-danger">*</span>
                                </label>
                                <Select
                                    styles={customStyles}
                                    options={status}
                                    defaultValue={status.filter(option => (option.value === (item.status_identifier || 'active')))}
                                    value={status.filter(option => (option.value === (item.status_identifier || 'active')))}
                                    onChange={(e) => this.handleSelectChange('status_identifier',e)}
                                />
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
                                    value={item.icon || ''}
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
                                        data-height="500"
                                        name="content"
                                        onChange={this.handleChange}>
                                    </div>
                                </div>
                            </div>

                            <div className="form-group">
                                <h5 className="mt-5">
                                    <strong className="mr-1">App Collections</strong>
                                    | <small> Select apps connected to this featured posts.</small>
                                </h5>
                                <hr/>

                                <div className="bg-white">
                                    <div className="input-group input-group-lg">
                                        <input type="text" className="form-control form-control-alt"
                                            placeholder="Search.."
                                            value={ this.state.search_input }
                                            onChange={ this.onSearchChange } />
                                        <div className="input-group-append">
                                            <span className="input-group-text border-0 bg-body">
                                                <i className="fa fa-fw fa-search"></i>
                                            </span>
                                        </div>
                                        <div className="input-group-append ">
                                            <button className="btn btn-sm btn-secondary" type="button"
                                                onClick={ this.onSearchClear.bind(this) }
                                            >
                                                Clear
                                            </button>
                                        </div>
                                    </div>

                                    { searchApps.length > 0 ?  (
                                        <div className="mt-3 pl-2">
                                            <div className="">
                                                <nav aria-label="Page navigation" >
                                                    <ReactPaginate
                                                        pageCount={this.state.pageCount}
                                                        initialPage={this.state.currentPage - 1}
                                                        forcePage={this.state.currentPage - 1}
                                                        pageRangeDisplayed={4}
                                                        marginPagesDisplayed={2}
                                                        previousLabel="&#x276E;"
                                                        nextLabel="&#x276F;"
                                                        containerClassName="pagination justify-content-center"
                                                        pageClassName="page-item"
                                                        activeClassName="active"
                                                        pageLinkClassName="page-link"
                                                        previousLinkClassName="page-link"
                                                        nextLinkClassName="page-link"
                                                        onPageChange={this.handleAppPaginateClick}
                                                        disableInitialCallback={true}
                                                    />
                                                </nav>
                                            </div>
                                            <h2 className="content-heading mb-3 px-2">
                                                <input className="mr-2" type="checkbox"  onChange={this.handleAllChecked}  value="checkedall" /> Check / Uncheck All
                                            </h2>
                                            <Checkbox {...{ collections: searchApps || [] }} clsName="search_apps" handleCheckChildElement={this.handleCheckChildElement}/>

                                            <div className="form-group">
                                                <button type="button"
                                                    className="btn btn-sm btn-dark"
                                                    onClick={this.addSelectedAppToCollections}
                                                >
                                                    <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                                                    </i> Add selected app to collections
                                                </button>
                                            </div>
                                        </div>
                                    ) : (
                                        <div>
                                            <small> Search apps that you want to connect</small>
                                        </div>
                                    ) }

                                    { connectedAppContent.length > 0 && connectedAppContent ? (
                                        <div className="mt-4">
                                            <h5 className="mt-5 mb-2">
                                                <strong className="mr-1">Connected Apps</strong>
                                            </h5>

                                           <table className="table table-hover">
                                            <thead>
                                                <tr>
                                                    {/* <th></th> */}
                                                    <th scope="col">Image</th>
                                                    <th scope="col">Title</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                               { connectedAppContent }

                                            </tbody>
                                            </table>

                                        </div>
                                    ) : null }

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
                                    onClick={this.onCreateFeaturedAppPost}
                                >
                                    <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                                    </i> {this.isForCreate ? 'Create Featured Post' : 'Update Featured Post'}
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
        )
    }
}

const element = document.getElementById('admin-featured-app');
if ( element ) {

    const props = (element.dataset.props) ? JSON.parse(element.dataset.props) : JSON.parse('{}');
    const status = (element.dataset.status) ? JSON.parse(element.dataset.status) : JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }

    if ( status )  {
        delete element.dataset.status;
    }
    ReactDOM.render(<FeaturedApp {...props} {...{status: status}} />, element );
}