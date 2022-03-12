import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import ReactPaginate from 'react-paginate';
import Progress from "react-progress-2";
import 'react-progress-2/main.css';
import { debounce, orderBy, uniqBy } from 'lodash';
import Checkbox  from '../Common/Checkbox';
import Select from 'react-select';
import DcmModal  from '../Common/DcmModal';
import { ButtonToolbar } from 'react-bootstrap';

export default class AppStore extends Component {

    constructor(props) {
        super(props);


        console.log('props', props);

        this.state = {
            item: {
                apps: [],
                categories: [],
            },
            selected_apps      : [],
            categoryCollections: props.categoryCollections,
            searchApps         : [],
            pageCount          : 1,
            currentPage        : 1,
            search_input       : '',
            isLoading          : false,
            modalShow          : false,
            modalShowErr       : false,
            errors             : []
        };


        this.onSearchChange              = this.onSearchChange.bind(this);
        this.onSearchChangeDebounce      = debounce(this.onSearchChangeDebounce, 500)
        this.handleAppPaginateClick      = this.handleAppPaginateClick.bind(this);
        this.handleAllChecked            = this.handleAllChecked.bind(this);
        this.handleCheckChildElement     = this.handleCheckChildElement.bind(this);
        this.addSelectedAppToCollections = this.addSelectedAppToCollections.bind(this);
        this.onCreateApp                 = this.onCreateApp.bind(this);


        this.toggle           = this.toggle.bind(this);
        this.toggleModalError = this.toggleModalError.bind(this);
    }


    componentDidMount() {

        const page = this.getQueryStringValue('page');
		this.setState(() => ({
            currentPage: page ? page : 1
        }));
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


    onSearchChange(evt) {
        const searchInput = evt.target.value || '';

        console.log('searchInput', searchInput)
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

        const resp = await axios.get( window.dcmUri['app_search'] + filters)

        try {
            if (resp.data.status == 'success') {

                const { data, meta } = resp.data;

                this.setState(() => ({
                    searchApps : data,
                    currentPage: meta.pagination.current_page,
                    pageCount  : meta.pagination.total_pages
                }));
                Progress.hide();
            } else {
                Progress.hide();
            }
        } catch (error) {
            Progress.hide();
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
            if (item.id === event.target.value)
                item.isChecked =  event.target.checked
        });

        const filtered = allApps.filter( item => {
            return item.isChecked === true;
        });

        if ( filtered.length > 0) {
            this.setState({
                selected_apps: filtered
            })
        }

    }

    addSelectedAppToCollections(event) {
        const { selected_apps, item } = this.state

        if ( selected_apps.length > 0) {

            let allApps = orderBy( uniqBy([...item.apps.concat(selected_apps)], 'id'), 'id', 'asc');

            this.setState({
                item: {
                    ...this.state.item,
                    apps: allApps
                }
            });

            this.onSearchClear()
        }
    }


    onCreateApp(event) {


        // loading...
        this.toggleIsLoading( true);

        axios.post( window.dcmUri['api_app_create'], this.state.item)
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
                window.location.reload();
            }
        })
        .catch(err => {

            console.log('err--> ', err);
            this.toggleIsLoading( false);
            this.setState({
                errors: err.response.data.errors,
                modalShowErr: true
            });
        });
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

        const { errors, isLoading, searchApps, item, categoryCollections } = this.state;


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
                    <td>
                        <img src={ app.image_url || '/img/default-app.png' } className="img-thumbnail" alt={ app.title }  width="40"/>
                    </td>
                    <td>
                        { app.title}
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

                <ButtonToolbar>
                        <DcmModal
                        content={`<span className="text-danger"><strong> Apps </strong></span> successfully created.`}
                        size="md"
                        show={this.state.modalShow}
                        onHide={this.toggle}
                        onExiting={ function(){
                            return window.location.href = `${item.dcm_detail_url}`;
                        }}
                    />
                </ButtonToolbar>

                <form acceptCharset="UTF-8" encType="multipart/form-data">

                    <div className="form-group">
                        <h5 className="mt-5">
                            <strong className="mr-1">Create From Playstore</strong>
                            | <small>Search apps from playstore</small>
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
                                        onClick={ this.onSearchClear.bind(this) } >
                                        Clear
                                    </button>
                                </div>
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
                                <Checkbox {...{ collections: searchApps || [], width: 60 }} clsName="search_apps" handleCheckChildElement={this.handleCheckChildElement}/>

                                <div className="form-group mb-4">
                                    <button type="button"
                                        className="btn btn-block btn-sm btn-dark"
                                        onClick={this.addSelectedAppToCollections}
                                    >
                                        <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                                        </i> Add selected apps for creation
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
                                    <strong className="mr-1"> Apps ready for creation</strong>
                                </h5>

                                <div className="form-group category_identifiers">
                                    <label htmlFor="category_identifiers">
                                        Select App Category <span className="text-danger">*</span>
                                    </label>
                                    <Select
                                        styles={customStyles}
                                        isMulti
                                        options={categoryCollections}
                                        value={ item.categories }
                                        onChange={(e) => this.handleSelectChange('categories',e)}
                                    />
                                </div>


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


                    <div className="row push">
                        <div className="col-lg-9 col-xl-9 offset-lg-3 mt-5">

                            <a href='#' className="btn btn-dark mr-1">
                                <i className="fa fa-arrow-left">
                                </i> Back
                            </a>
                            <button type="button"
                                // className="btn btn-success"
                                className={`btn btn-success ${connectedAppContent.length > 0 && connectedAppContent ? '' : 'disabled'}`}
                                onClick={this.onCreateApp}
                            >
                                <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                                </i> Create new app
                            </button>
                        </div>

                    </div>

                </form>
            </div>
        )
    }
}


const element = document.getElementById('admin-app-store');
if ( element ) {

    const props = (element.dataset.props) ? JSON.parse(element.dataset.props) : JSON.parse('{}');
    const letters = (element.dataset.letters) ? JSON.parse(element.dataset.letters) : JSON.parse('{}');
    const categories = (element.dataset.categories) ? JSON.parse(element.dataset.categories) : JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }
    if ( letters )  {
        delete element.dataset.letters;
    }
    if ( categories )  {
        delete element.dataset.categories;
    }

    ReactDOM.render(<AppStore {...props} {...{letters: letters, categoryCollections: categories}} />, element );
}