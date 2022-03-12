import React, { Component } from 'react';
import ReactDOM from 'react-dom';
import axios from 'axios';
import DcmModal  from '../Common/DcmModal';
import { ButtonToolbar, Tabs, Tab } from 'react-bootstrap';
import Seo  from '../Common/Seo';
import 'react-progress-2/main.css';
import Select from 'react-select';
import AppMoreDetail from './AppMoreDetail';
import AppVersion from './AppVersion';
import Dropzone from 'react-dropzone';
import { createFormData } from '../Common/Utility';
import { first } from 'lodash';
import InputTag  from '../Common/InputTag';

import Progress from "react-progress-2";
import 'react-progress-2/main.css';

export default class AppDetail extends Component {

    constructor(props) {
        super(props);

        this.isForCreate = (props.page_type || 'edit')  == 'create' ? true : false;
        this.seoOption = React.createRef();
        this.state = {
            item: {
                app_id: '',
                title: '',
                slug: '',
                short_description: '',
                description: '',
                app_link: '',
                app_image_url: '',
                app_image_preview: '',
                current_ratings: '',
                total_ratings: '',
                details: '',
                seo_title: '',
                seo_keyword: {},
                seo_description: '',
                status_identifier: '',
                categories: [],
                developers: [],
                more_details: [],
                versions: [],
                screenshots: [],
                tags: []
            },
            isLoading: false,
            isLoading: false,
            modalShow: false,
            modalShowErr: false,
            errors: [],
            status: props.status ,
            categoryCollections: props.categoryCollections,
            developerCollections: props.developerCollections,
            screenshot_previews: []
        };

        this.handleOnSubmit         = this.handleOnSubmit.bind(this);
        this.handleSearchAppDetails = this.handleSearchAppDetails.bind(this);
        this.handleChange           = this.handleChange.bind(this);
        this.hasErrorFor            = this.hasErrorFor.bind(this);
        this.renderErrorFor         = this.renderErrorFor.bind(this);

        this.toggle           = this.toggle.bind(this);
        this.toggleModalError = this.toggleModalError.bind(this);

        this.onConfirmDeleteDetail = this.onConfirmDeleteDetail.bind(this);
        this.onDropScreenshot      = this.onDropScreenshot.bind(this);
        this.onDropAppImage        = this.onDropAppImage.bind(this);
        this.onInputTagChange      = this.onInputTagChange.bind(this);

    }


    componentDidMount() {

        console.log('props', this.props);

        var that = this;
        jQuery('.js-summernote:not(.js-summernote-enabled)').each((index, element) => {
            let el = jQuery(element);

            const contentName =  el.attr('name');
            el.addClass('js-summernote-enabled').summernote({
                placeholder: 'eg: Place your content here.',
                height      : el.data('height') || 350,
                minHeight   : el.data('min-height') || null,
                maxHeight   : el.data('max-height') || null,
                disableDragAndDrop: true,
                callbacks: {

                    onChange: function (content) {
                        var html = content.trim();
                        if ( html ) {
                            that.setState({
                                item: {
                                    ...that.state.item,
                                    [contentName]: html || ''
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

        const _description = this.props.description || '';
        const _short_description = this.props.short_description || '';
        // if ( content ) {
            jQuery('.js-summernote').each(function(){
                const contentName =  $(this).attr('name');
                if ( contentName === 'description')
                    $(this).summernote('code','<div>'+_description+'</div>');
                else if ( contentName === 'short_description')
                    $(this).summernote('code','<div>'+_short_description +'</div>');
            });
        // }


        if ( this.isForCreate ) {

            const defaultOps = {
                pageindex        : this.props.pageindex || '#',
                app_id           : '',
                title            : '',
                slug             : '',
                short_description: '',
                description      : '',
                app_link         : '',
                app_image_url    : '',
                app_image_preview: '',
                current_ratings  : '',
                total_ratings    : '',
                details          : '',
                seo_title        : '',
                seo_keyword      : {},
                seo_description  : '',
                status_identifier: '',
                categories       : [],
                developers       : [],
                more_details     : [],
                versions         : [],
                screenshots      : [],
                tags             : []
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


        let urlTabHash = window.location.hash.replace(/^#!/, '');
        if ( urlTabHash ) {

            urlTabHash = urlTabHash.replace(/#/g,'')
            this.onTabSelect( urlTabHash );
        }
    }


    onInputTagChange(evt) {
        this.setState({
            item: {
                ...this.state.item,
                tags: evt
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

    handleOnSubmit(event) {
        event.preventDefault();

        const { screenshot_previews } = this.state;
        const { seo_title, seo_description, seo_keyword} = this.seoOption.current.state;
        const item = {...this.state.item, seo_title, seo_description, seo_keyword}

        if ( screenshot_previews.length > 0) {
            item.screenshots = { ...item.screenshots, ...{for_uploads: screenshot_previews} };
        }
        
        // loading...
        this.toggleIsLoading( true);

        // remove uneeded data
        delete item.categoryCollections;
        delete item.developerCollections;
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
                // console.log('create', this.state);
            });

        } else {

            _itemFormData.append('_method', 'PATCH')
            axios.post( window.dcmUri['resource'] + '/' + item.id || 0, _itemFormData)
            .then( resp => {

                if ( resp.data && resp.data.data) {
                    this.setState({
                        isLoading: false,
                        item: {
                            ...this.state.item,
                            dcm_detail_url: resp.data.data.dcm_detail_url || '#'
                        }
                    });
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

    async handleSearchAppDetails(event) {
        event.preventDefault();

        Progress.show();
        const { item } = this.state;

        console.log('update item', item);
        const resp = await axios.post( window.dcmUri['app_details'], {app_id: item.app_id })

        try {
            if (resp.data.status == 'success') {

                console.log('resp', resp);
                const { data } = resp.data;

                const item = {...this.state.item, ...data}

                
                this.seoOption.current.setState(item)
                this.setState(() => ({
                    item : item
                }));

                jQuery('.js-summernote').each(function(){
                    const contentName =  $(this).attr('name');
                    if ( contentName === 'description')
                        $(this).summernote('code','<div>'+item.description+'</div>');
                    else if ( contentName === 'short_description')
                        $(this).summernote('code','<div>'+item.short_description +'</div>');
                });

                Progress.hide();
            } else {
                Progress.hide();
            }
        } catch (err) {
            Progress.hide();
            this.toggleIsLoading( false);
            this.setState({
                errors: err.response.data.errors,
                modalShowErr: true
            });
        }

    }

    handleSelectChange( identifier, option ) {
        this.setState({
            item: {
                ...this.state.item,
                [identifier]:  (option.value) ? option.value : option
            }
        });
    }


    handleChange (event) {
        
        const evtName = event.target.name;
        const evtValue = event.target.value;
        this.setState((prevState, props) => ({
            item: {
                ...prevState.item,
                [evtName]: evtValue
            },
        }));
    }

    onConfirmDeleteDetail(index, evt) {
        this.setState({
            item: {
                ...this.state.item,
                more_details: this.state.item.more_details.filter((s, sidx) => index !== sidx)
            }
        });

    }

    appDetailAddMore() {

        this.setState({
            item: {
                ...this.state.item,
                more_details: this.state.item.more_details.concat([{

                                                            title      : "",
                                                            value      : ""
                                                        }])
            }
        });
    }

    onAppDetailChange( index, value, identifier ) {

        const newMoreDetails = this.state.item.more_details.map((item, sidx) => {
            if (index !== sidx) return item;
            return { ...item, [identifier]: value };
        });

        this.setState({
            item: {
                ...this.state.item,
                more_details: newMoreDetails
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


    toggleIsLoading( isLoading = false) {
        this.setState({
            isLoading: isLoading
        });
    }


    onTabSelect(value) {
        //
        if(history.replaceState) {
            history.replaceState(null, null, '#' + value);
        } else if(history.pushState) {
            history.pushState(null, null, '#' + value);
        }
        else {
            location.hash = '#' + value;
        }

        this.setState({
            key: value
        });
    }

    onDropScreenshot(acceptedFiles ) {

        const previewImages = acceptedFiles.map( function( item ) {
            item.preview = URL.createObjectURL(item);
            URL.revokeObjectURL(item);
            return item;
        });

        const item = this.state.screenshot_previews;
        // if ( previewImages.length > 0) {
        //     item.screenshot_previews = { ...item.screenshot_previews, ...previewImages};
        // }

        // console.log('item', item);
        this.setState({
            screenshot_previews: previewImages,
        });
    }

    onRemoveScreenshot(index, type = 'screenshots' ) {
        const filteredItem = this.state.item[type].filter((item, i) => i !== index);
        if ( type == 'screenshots') {
            this.setState({
                item: {
                    ...this.state.item,
                    [type]: filteredItem
                }
            });
        } else if ( type == 'screenshot_previews') {
            this.setState({
                [type]: filteredItem,
            });
        }
    }

    onDropAppImage( files ) {
        const appImages = files .map( function( item ) {
            item.preview = URL.createObjectURL(item);
            URL.revokeObjectURL(item);
            return item;
        });
        const appImage = first( appImages )
        this.setState({
            item: {
                ...this.state.item,
                app_image_url: appImage,
                app_image_preview: appImage.preview || '',
            }
        });

        // console.log('item', this.state.item);
    }

    render() {

        const { errors, isLoading, item, categoryCollections, developerCollections, status, screenshot_previews } = this.state;
        const tags = this.props.tags;

        const styles = {

            avatar: {
                width: '60%'
            },
            fit_image: {
                width: 'auto',
                height: 100,
                objectFit: 'cover'
            }
        }

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

        let AppMoreDetailContent = [];
        if ( item.more_details ) {
            AppMoreDetailContent = item.more_details.map((app, index) => (
                <AppMoreDetail key={index} app={app} index={index}
                                onHandleDeleteClick={this.onConfirmDeleteDetail }
                                onHandleDetailChange={ this.onAppDetailChange.bind( this) } />
            ));
        }


        let appScreenShots = [];
        if ( item.screenshots ) {
            appScreenShots = item.screenshots.map((app, index) => {
                if ( app.id ) {
                    return (
                        <div key={index} className="col-lg-2 col-md-2 col-xs-2 col-3 ">
                            <img src={app.image_link  } className="img-thumbnail img-fluid" style={ styles.fit_image }/>
                            <button
                            className="btn btn-sm btn-dark btn-block"
                            type="button"
                            onClick={ this.onRemoveScreenshot.bind( this, index, 'screenshots') }
                            ><small><i className="fa fa-trash"></i> {Math.round((app.size || 1) / Math.pow(2, 10)).toLocaleString()} KB</small></button>
                        </div>
                    )
                }
            });
        }

        let tempScreenShotPreviews = [];
        if ( screenshot_previews ) {
            tempScreenShotPreviews = screenshot_previews.map((app, index) => (
                <div key={index}  className="col-lg-2 col-md-2 col-2 ">
                    <img src={app.preview} className="img-thumbnail img-fluid" style={ styles.fit_image }/>
                    <button className="btn btn-sm btn-dark btn-block"
                       type="button"
                       onClick={ this.onRemoveScreenshot.bind( this, index, 'screenshot_previews') }
                    ><small><i className="fa fa-trash mr-1"></i> {Math.round((app.size || 2) / Math.pow(2, 10)).toLocaleString()} KB</small></button>

                </div>
            ));
        }
        
        return (
            <div className="mb-5 pb-5">

              { this.state.modalShowErr ? <DcmModal
                    content={errors}
                    size="md"
                    show={this.state.modalShowErr}
                    onHide={this.toggleModalError}
                /> : null }

                <Progress.Component
                    style={{ background: '#00a680', height: '5px' }}
                    thumbStyle={{ background: '#00a680', height: '5px' }}
                />

                <div className="row push ">
                    <div className="col-lg-3">
                        <div className="avatar-wrapper">

                            <div id="avatar"></div>

                            <div className="text-center">
                                <div className="avatar-preview" >
                                    <img style={styles.avatar} className="avatar rounded-circle img-thumbnail img-responsive mt-3 mb-4"
                                    src={ item.app_image_preview || item.app_image_url || '/img/default-app.png'} alt={item.full_name || 'App Image'} />
                                    <h6 className="text-muted">App Image</h6>
                                </div>
                                <Dropzone
                                    multiple={false}
                                    accept="image/*"
                                    onDrop={this.onDropAppImage}>
                                    {({getRootProps, getInputProps, isDragActive, isDragReject}) => (
                                        <section>
                                        <div {...getRootProps({className: 'dropzone'})}>
                                            <input {...getInputProps()} />
                                            <p className="pt-3">
                                                {!isDragActive && 'Drag n drop your image here, or click to select your image'}
                                                {isDragActive && !isDragReject && "Drop it like it's hot!"}
                                                {isDragReject && "File type not accepted, sorry!"}
                                            </p>
                                        </div>
                                        </section>
                                    )}
                                </Dropzone>
                            </div>
                        </div>
                    </div>

                    <div className="col-lg-9 col-xl-9">

                        <Tabs
                                id="controlled-tab-example"
                                activeKey={this.state.key}
                                onSelect={ this.onTabSelect.bind( this )}
                        >
                            <Tab eventKey="app_details" title="App Details" name="app_tab">
                                <div className="mt-3 pl-2">
                                    <div className="form-group">
                                        <label htmlFor="app_id">App ID <small className="text-danger"> * </small></label>

                                        <div className="input-group mb-3">
                                            <input type="text"
                                                className={`form-control ${this.hasErrorFor('app_id') ? 'is-invalid' : ''}`}
                                                placeholder="eg: Enter unique app id here."
                                                aria-label="eg: Enter unique app id here."
                                                aria-describedby="app_id"
                                                name="app_id"
                                                defaultValue={item.app_id}
                                                onChange={this.handleChange} />
                                                <div className="input-group-append">
                                                    <button onClick={this.handleSearchAppDetails} className="btn btn-success" type="button" id="app_id">Get Details</button>
                                                </div>
                                        </div>
                                        { this.renderErrorFor('app_id') }
                                    </div>

                                    <div className="form-group">
                                        <label htmlFor="title">App Name <small className="text-danger"> * </small></label>
                                        <input
                                            type="text"
                                            name="title"
                                            className={`form-control ${this.hasErrorFor('title') ? 'is-invalid' : ''}`}
                                            placeholder="eg: Enter app title here"
                                            defaultValue={item.title}
                                            onChange={this.handleChange}
                                        />
                                        { this.renderErrorFor('title') }
                                    </div>

                                    <div className="form-group">
                                        <label htmlFor="app_id">Slug</label>
                                        <input
                                            type="text"
                                            name="slug"
                                            className={`form-control ${this.hasErrorFor('slug') ? 'is-invalid' : ''}`}
                                            placeholder="eg: Enter slug here."
                                            defaultValue={item.slug}
                                            onChange={this.handleChange}
                                        />
                                        { this.renderErrorFor('slug') }
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="title">
                                            App Status <span className="text-danger">*</span>
                                        </label>
                                        <Select
                                            styles={customStyles}
                                            options={status}
                                            defaultValue={status.filter(option => (option.value === (item.status_identifier || 'active')))}
                                    value={status.filter(option => (option.value === (item.status_identifier || 'active')))}
                                            onChange={(e) => this.handleSelectChange('status_identifier',e)}
                                        />
                                    </div>
                                    <div className="form-group category_identifiers">
                                        <label htmlFor="category_identifiers">
                                            Select App Developer <span className="text-danger">*</span>
                                        </label>
                                        <Select
                                            styles={customStyles}
                                            isMulti
                                            options={developerCollections}
                                            value={ item.developers }
                                            onChange={(e) => this.handleSelectChange('developers',e)}
                                        />
                                        { this.renderErrorFor('developers') }
                                    </div>

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
                                        { this.renderErrorFor('categories') }
                                    </div>

                                    <div className="form-group tags">
                                        <label htmlFor="tags">
                                            Setup App Tags
                                        </label>
                                        <InputTag placeholder="Enter tags" value={ (item.tags.length > 0) ? item.tags : this.props.tags} onChange={this.onInputTagChange } />
                                    </div>
                                    <div className="form-group">
                                        <label htmlFor="app_link">Google Play Url</label>
                                        <input
                                            type="text"
                                            name="app_link"
                                            className={`form-control ${this.hasErrorFor('app_link') ? 'is-invalid' : ''}`}
                                            placeholder="eg: Enter google play store url here."
                                            defaultValue={item.app_link}
                                            onChange={this.handleChange}
                                        />
                                        { this.renderErrorFor('slug') }
                                    </div>

                                    <div className="form-group">
                                        <label htmlFor="short_description">Short Description</label>
                                        <div
                                            className="form-control js-summernote"
                                            data-height="120"
                                            name="short_description"
                                            placeholder="eg: Short description about your app."
                                            onChange={this.handleChange}>
                                        </div>
                                        { this.renderErrorFor('short_description') }
                                    </div>

                                    <div className="form-group">
                                        <label htmlFor="description">Full Description</label>
                                        <div
                                            className="form-control js-summernote"
                                            data-height="500"
                                            name="description"
                                            placeholder="eg: Full description about your app."
                                            onChange={this.handleChange}>
                                        </div>
                                        { this.renderErrorFor('description') }
                                    </div>

                                    <div className="form-group">
                                        <label htmlFor="app_link"> App Screenshots </label>
                                        <Dropzone
                                            multiple={true}
                                            accept="image/*"
                                            onDrop={this.onDropScreenshot}>
                                            {({getRootProps, getInputProps, isDragActive, isDragReject}) => (
                                                <section>
                                                <div {...getRootProps({className: 'dropzone'})}>
                                                    <input {...getInputProps()} />
                                                    <p className="pt-3">
                                                        {!isDragActive && 'Drag n drop your image here, or click to select your image'}
                                                        {isDragActive && !isDragReject && "Drop it like it's hot!"}
                                                        {isDragReject && "File type not accepted, sorry!"}
                                                    </p>
                                                </div>
                                                </section>
                                            )}
                                        </Dropzone>
                                        {  appScreenShots ?  (
                                            <div className="row mt-2 mb-2">
                                                {appScreenShots}
                                            </div>
                                            ) : null
                                        }
                                        {  tempScreenShotPreviews.length > 0 && tempScreenShotPreviews ?  (
                                            <div>
                                                <h3 className="content-heading">Preparing to upload new app images</h3>
                                                <div className="row mt-2 mb-2">
                                                    {tempScreenShotPreviews}
                                                </div>
                                            </div>
                                            ) : null
                                        }
                                    </div>
                                </div>
                            </Tab>

                            <Tab eventKey="additional_input" title="Additional Info" name="additional_input">
                                <div className="mt-3 pl-2">
                                    <div className="form-group">
                                        <label htmlFor="current_ratings">App Ratings</label>
                                        <input
                                            type="text"
                                            name="current_ratings"
                                            className={`form-control ${this.hasErrorFor('current_ratings') ? 'is-invalid' : ''}`}
                                            placeholder="eg: App ratings"
                                            defaultValue={item.current_ratings}
                                            onChange={this.handleChange}
                                        />
                                        { this.renderErrorFor('current_ratings') }
                                    </div>


                                    <div className="form-group">
                                        <label htmlFor="current_ratings">App Total Ratings</label>
                                        <input
                                            type="text"
                                            name="total_ratings"
                                            className={`form-control ${this.hasErrorFor('total_ratings') ? 'is-invalid' : ''}`}
                                            placeholder="eg: App total ratings"
                                            defaultValue={item.total_ratings}
                                            onChange={this.handleChange}
                                        />
                                        { this.renderErrorFor('total_ratings') }
                                    </div>
                                    <h2 className="content-heading">
                                        <span className="mr-2">Additional Custom Input</span>
                                        <button className="btn btn-dark btn-sm"
                                            type="button"
                                            onClick={ this.appDetailAddMore.bind(this) }>
                                            <i  className="fa fa-plus"></i> Add More
                                        </button>
                                    </h2>

                                    {  item.more_details ?  (
                                            <div className="form-group">
                                                {AppMoreDetailContent.length > 0 && AppMoreDetailContent}
                                            </div>
                                        ) : (
                                            ""
                                        )
                                    }

                                </div>

                            </Tab>

                            <Tab eventKey="app_version" title="Apk Versions" name="app_version">

                                <div className="pl-2">
                                    <AppVersion {...this.props} />
                                </div>
                            </Tab>

                            <Tab eventKey="seo" title="SEO" name="seo">
                                <div className="pl-2">
                                    <Seo {...this.props} ref={this.seoOption} />
                                </div>
                            </Tab>
                        </Tabs>
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
                                onClick={this.handleOnSubmit}
                            >
                                <i className={`mr-1 ${isLoading ? 'fa fa-spin fa-spinner' : 'fa fa-check-circle'}`}>
                                </i> {this.isForCreate ? 'Create App' : 'Update App'}
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
            </div>
        )
    }
}


const element = document.getElementById('admin-app-detail');
if ( element ) {

    const props = (element.dataset.props) ? JSON.parse(element.dataset.props) : JSON.parse('{}');
    const status = (element.dataset.status) ? JSON.parse(element.dataset.status) : JSON.parse('{}');
    const categories = (element.dataset.categories) ? JSON.parse(element.dataset.categories) : JSON.parse('{}');
    const developers = (element.dataset.developers) ? JSON.parse(element.dataset.developers) : JSON.parse('{}');

    if ( props )  {
        delete element.dataset.props;
    }

    if ( status )  {
        delete element.dataset.status;
    }

    if ( categories )  {
        delete element.dataset.categories;
    }

    if ( developers )  {
        delete element.dataset.developers;
    }

    ReactDOM.render(<AppDetail {...props} {...{status: status, categoryCollections: categories, developerCollections: developers}} />, element );
}