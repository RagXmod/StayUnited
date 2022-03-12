import React from 'react';
import InputTag  from '../Common/InputTag';

export default class Seo extends React.Component {

    constructor(props) {
        super(props);

        this.state = {
            seo_title      : props.seo_title || '',
            seo_keyword    : props.seo_keyword || '',
            seo_description: props.seo_description || ''
        };

        this.handleInputChange = this.handleInputChange.bind(this);
        this.onInputTagChange = this.onInputTagChange.bind(this);
    }

    handleInputChange(event) {
        this.setState({ [event.target.name]: event.target.value });
    }

    onInputTagChange(evt) {
        this.setState({
            seo_keyword: evt
        });
    }

    render() {

        const {seo_title, seo_description, seo_keyword} = this.state;

        return (

            <div className="form-group">
                 <h5 className="mt-5">
                    <strong className="mr-1">SEO Options</strong>
                    | <small>Optimize Your Page rank</small>
                 </h5>
                 <hr/>

                <div className="form-group">
                    <label htmlFor="title">
                        SEO Title
                    </label>
                    <input
                        type="text"
                        name="title"
                        className="form-control"
                        placeholder="eg: Seo Title Here"
                        name="seo_title"
                        value={seo_title}
                        onChange={this.handleInputChange}
                    />
                </div>
                <div className="form-group">
                    <label htmlFor="title">
                        SEO Description
                    </label>
                    <textarea
                        type="text"
                        className="form-control"
                        placeholder="eg: SEO Meta Descriptions"
                        name="seo_description"
                        value={seo_description}
                        onChange={this.handleInputChange}
                        maxLength={160}
                    ></textarea>
                    <small className="text-danger"> 160 characters only </small>
                </div>


                <div className="form-group Seo">
                    <label htmlFor="title">
                        SEO Keywords
                    </label>
                    <InputTag value={seo_keyword} onChange={this.onInputTagChange } />
                </div>

            </div>
        );
    }
}