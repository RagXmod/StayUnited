import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import AppContent  from './AppContent';
import ReactPaginate from 'react-paginate';
import Progress from "react-progress-2";
import 'react-progress-2/main.css';
import debounce from 'lodash.debounce';

import { Button, Modal } from 'react-bootstrap';

export default class App extends Component {

    constructor(props) {
        super(props);

        this.isForCreate = (props.page_type || 'edit')  == 'create' ? true : false;
        this.state = {

            apps: [],
			completed: false,
			pageCount: 1,
            currentPage: 1,
            search_input: '',
            selected_letter: 'All',
            selected_delete: '',

            isLoading: false,
            modalShow: false,
            modalShowErr: false,
            errors: [],
            letters: []
        };


        this.handleAppPaginateClick = this.handleAppPaginateClick.bind(this);
        this.onSearchChange         = this.onSearchChange.bind(this);
        this.onSearchChangeDebounce = debounce(this.onSearchChangeDebounce, 500)

        this.onConfirmDelete = this.onConfirmDelete.bind(this);
        this.toggle = this.toggle.bind(this);
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

    async componentDidMount() {

		const page = this.getQueryStringValue('page');
		await Promise.resolve(
			this.setState(() => ({
                currentPage: page ? page : 1,
                letters: this.props.letters || [],
             }))
		);
		this.getAppData();
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

    onConfirmDelete( app ){

        this.toggle();
        // ask before delete
        this.setState({
            selected_delete: app
        });
    }

    delete( id ){
        let items = this.state.apps.filter(item => item.id !== id);
        axios.delete( window.dcmUri['resource'] + '/' + id)
            .then( data => {
                this.setState({
                    apps: items,
                    selected_delete: ''
                });
                this.toggle();

            })
            .catch(err => {
                this.setState({
                    selected_delete: ''
                });
                this.toggle();
            });
    }

    async onLetterChange(item, evt) {
        const selectedLetter = item || 'All';
        await Promise.resolve(this.setState(() => ({ selected_letter: selectedLetter })));

        this.getAppData();
    }

	async handleAppPaginateClick(data) {

		const page = data.selected >= 0 ? data.selected + 1 : 0;
		await Promise.resolve(this.setState(() => ({ currentPage: page })));

		this.getAppData();
	}

	async getAppData() {
        Progress.show();

        const filters = '?page=' + this.state.currentPage
                + '&letter=' + this.state.selected_letter
                + '&q=' + this.state.search_input ;

        const response = await axios.get( window.dcmUri['resource'] + filters);

        try {
            if (response.data.status == 'success') {

                const { data, meta } = response.data;

                this.setState(() => ({
                	apps       : data,
                	currentPage: meta.pagination.current_page,
                	pageCount  : meta.pagination.total_pages
                }));
                window.scrollTo(0, 0);
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


    toggleIsLoading( isLoading = false) {
        this.setState({
            isLoading: isLoading
        });
    }

    render() {

        const { selected_letter, selected_delete } = this.state;
        const styles = {

            letter_link: {
                color: '#ffffff'
            },
            letter_active: {
                backgroundColor: '#5c988b',
                borderColor    : '#5c988b'
            }
        }


        const Apps = this.state.apps.map((app, index) => (
			<AppContent key={app.id} app={app} index={index} onHandleDeleteClick={this.onConfirmDelete } />
        ));

        const loopLetters = this.state.letters.map(item => (

            <a key={item}  style={ item == selected_letter ? {...styles.letter_link, ...styles.letter_active } : styles.letter_link }
                className="btn btn-secondary btn-circle"
                title={ item }
                onClick={ this.onLetterChange.bind( this, item ) }>
                { item }
            </a>
        ));

        return (
            <div>

                <Modal show={this.state.modalShow} onHide={this.toggle}>
                    <Modal.Header closeButton>
                    <Modal.Title> Confirm App Deletion</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>Are you sure you want to delete <strong>{ selected_delete.title }</strong> ?</Modal.Body>
                    <Modal.Footer>
                    <Button variant="secondary" onClick={this.toggle}>
                        Close
                    </Button>
                    <Button variant="danger" onClick={this.delete.bind(this, selected_delete.id)}>
                        Confirm Delete
                    </Button>
                    </Modal.Footer>
                </Modal>

                <div className="bg-white">
                    <div className="content">
                        <h2 className="content-heading pt-0">Search your favorite apps or games</h2>
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
                        </div>

                        <div className="form-group mt-3">
                            <div className="text-center">
                                { loopLetters }
                            </div>
                        </div>
                    </div>
                </div>
                <Progress.Component
                    style={{ background: '#00a680', height: '5px' }}
                    thumbStyle={{ background: '#00a680', height: '5px' }}
                />

                <div className="block block-themed">
                    <div className="block-header bg-light">
                        <h3 className="block-title">
                            Browse all your play store
                        </h3>
                    </div>

                    <div className="block-content block-content-full block-content-sm bg-body-light">
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

                    <div className="block-content block-content-admin block-content-no-pad row">
                        {Apps.length > 0 && Apps}
                    </div>
                    <div className="block-content block-content-full block-content-sm bg-body-light">
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

                </div>
            </div>

        );
    }
}

const element = document.getElementById('admin-app');
if ( element ) {

    const props = (element.dataset.props) ? JSON.parse(element.dataset.props) : JSON.parse('{}');
    const letters = (element.dataset.letters) ? JSON.parse(element.dataset.letters) : JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }
    if ( letters )  {
        delete element.dataset.letters;
    }

    ReactDOM.render(<App {...props} {...{letters: letters}} />, element );
}